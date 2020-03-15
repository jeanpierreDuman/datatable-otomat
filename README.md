# datatable-otomat

### Datatable example in Controller
    /**
     * @Route("/society/salary/datatable", name="datatable_society_salary")
     */
    public function datatableSalary(Request $request, DatatableService $datatableService)
    {
        $idSociety = $request->request->get('idSociety');
        
        $datatableService->initDatatable($request->request, [
            'firstname',
            'lastname',
            'email',
            'dateBirth',
            'dateArrived'
        ], Salary::class, [
            'society' => $idSociety
        ]);
        
        $salaries = $datatableService->getParams('data');
        
        $array = [];
        
        foreach($salaries as $salary) {
                        
            $array[] = [
                'firstname' => $salary->getFirstname(),
                'lastname' => $salary->getLastname(),
                'email' => $salary->getEmail(),
                'dateBirth' => $salary->getDateBirth()->format('d/m/Y'),
                'dateArrived' => $salary->getDateArrived()->format('d/m/Y')
            ];
        }

        $jsonData = $datatableService->getJsonData($array);
        
        return new JsonResponse($jsonData);
    }
    
### Datatable routing services
    App\Service\DatatableService:
        class: App\Service\DatatableService
        arguments: ['@doctrine.orm.entity_manager']

### Datatable example in template.twig
    var table = getDatatable('#table-society-salary', '{{ path('datatable_society_salary') }}', [
        {data: 'firstname'},
        {data: 'lastname'},
        {data: 'email'},
        {data: 'dateBirth'},
        {data: 'dateArrived'}
    ], {
        'idSociety': '{{ society.id }}'
    });

### Don't forget javascript
    <script src="{{ asset('public/assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/js/datatable.js') }}"></script>    
    <script src="{{ asset('public/assets/js/pnotify.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    
### Don't forget css
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    
