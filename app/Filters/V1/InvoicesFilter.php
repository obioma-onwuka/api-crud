<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class InvoicesFilter extends ApiFilter {

    $table->foreignId('customer_id');
    $table->integer('amount');
    $table->string('status');
    $table->dateTime('billed_date');
    $table->dateTime('paid_date')->nullable();

    protected $safeParams = [

        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq', 'gt', 'lt']

    ];

    protected $columnMap = [

        'customerId' => 'customer_id',
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date',

    ];

    protected $operatorMap = [

        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',

    ];

    public function transform(Request $request){

        $eloQuery = [];

        foreach($this->safeParams as $param => $operators){

            $query = $request->query($param);

            if(!isset($query)){

                continue;

            }

            $column = $this->columnMap[$param] ?? $param;

            foreach($operators as $operator){

                if(isset($query[$operator])){

                    $eloQuery[] =[$column, $this->operatorMap[$operator], $query[$operator]];

                }

            }

        }

        return $eloQuery;

    }

}