<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;

class DatatableService {
    
    private $em;
    private $request;
    private $columns;
    private $class;
    private $where;

    public function initDatatable($request, array $columns = [], $class = null, array $where = [])
    {
        $this->request = $request;
        $this->columns = $columns;
        $this->class = $class;
        $this->where = $where;
    }

    public function __construct(EntityManager $em) 
    {
        $this->em = $em;
    }
    
    public function getData($where, $order, $limit, $start)
    {
        $entities = $this->em->getRepository($this->class)->findBy($where);
        $totalData = count($entities);
        
        $entitiesFiltered = $this->em->getRepository($this->class)->findBy(
            $where, $order, $limit, $start
        );
        
        $totalFiltered = count($entitiesFiltered);
        
        return [
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered,
            'data' => $entitiesFiltered
        ];
    }

    public function getParams($value = null) 
    {
        $orderColumn = $this->getColumns()[$this->request->get('order')[0]['column']];
        $dirOrderColumn = $this->request->get('order')[0]['dir'];
        $limit = $this->request->get('length');
        $start = $this->request->get('start');

        $data = $this->getData(            
            $this->where,
            [$orderColumn => $dirOrderColumn],
            $limit,
            $start
        );
        
        if($value !== null) {
            return $data[$value];
        }
        
        return $data;
    }
    
    public function getJsonData(array $array = []) {

        $json = [
            "draw" => intval($this->request->get('draw')),
            "recordsTotal" => intval($this->getParams('totalFiltered')),
            "recordsFiltered" => intval($this->getParams('totalData')),
            "data" => $array
        ];

        return $json;
    }
    
    public function getColumns() {
        return $this->columns;
    }
}





