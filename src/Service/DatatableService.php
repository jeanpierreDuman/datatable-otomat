<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;

class DatatableService {
    
    private $em;
    private $params;
    private $columns;
    private $class;
    private $where;

    public function initDatatable($params, array $columns = [], $class = null, array $where = [])
    {
        $this->params = $params;
        $this->columns = $columns;
        $this->class = $class;
        $this->where = $where;
        $this->getMainData();
    }

    public function __construct(EntityManager $em) 
    {
        $this->em = $em;
    }
    
    public function getColumns() {
        return $this->columns;
    }

    public function getConditions($where, $order, $limit, $start)
    {
        $societies = $this->em->getRepository($this->class)->findAll();
        $totalData = count($societies);
        
        $societiesFiltered = $this->em->getRepository($this->class)->findBy(
            $where, $order, $limit, $start
        );
        
        $totalFiltered = count($societiesFiltered);
        
        return [
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered,
            'data' => $societiesFiltered
        ];
    }

    public function getMainData() 
    {
        $orderColumn = $this->getColumns()[$this->params->get('order')[0]['column']];
        $dirOrderColumn = $this->params->get('order')[0]['dir'];
        $limit = $this->params->get('length');
        $start = $this->params->get('start');

        $data = $this->getConditions(            
            $this->where,
            [$orderColumn => $dirOrderColumn],
            $limit,
            $start
        );
        
        return $data;
    }
    
    public function getJson(array $array = []) {

        $json = [
            "draw" => intval($this->params->get('draw')),
            "recordsTotal" => intval($this->getMainData()['totalFiltered']),
            "recordsFiltered" => intval($this->getMainData()['totalData']),
            "data" => $array
        ];

        return $json;
    }
}





