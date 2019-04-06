<?php
/* Create the Linear model -by Kaustubh B at March 28 2019 */
include_once ("Neuron.php");
//ini_set('memory_limit', '-1');

class Linear_Model
{
    public $model=array();
    //this mat is a 3D matrix ...
    function add_layer($x_json,$default_weight=0,$neuron_count=1,$activation='sigmoid'){
        $layer=array();
        $ctr=0;
        for($i=0;$i<$neuron_count;$i++)
        {
            array_push($layer,new Neuron($x_json,$default_weight,$ctr,$activation));
            $ctr++;
        }
        array_push($this->model,$layer);
    }

    public function train($x_json,$y_json,$steps){
      //steps  ctr epochs will come here and we take the corrosponding index steps..
      for($step=0;$step<$steps;$step++){
            $ctr=$step;
            for ($i=0;$i<sizeof($this->model);$i++){
                $layer=$this->model[$i];
                if($i==0){
                    //input layer neurons will take the x_json as input ...
                    $arr=array();
                    $obj=new stdClass();
                    $obj->input=array();
                    for($j=0;$j<sizeof($layer);$j++){
                            array_push($obj->input,$layer[$j]->learn($x_json,$y_json,60,$ctr));
                    }
                    array_push($arr,$obj);
                }else{
                    if($step<$ctr){
                        $ctr=$step-$ctr;
                    }
                    $obj2=$arr;
                    $obj=array();
                    $obj->input=array();

                    for($j=0;$j<sizeof($layer);$j++){
                        $neuron=$layer[$j];
                        //debug input and output json ... remaining things are okay
                       // var_dump($obj2);
                       // echo "----------";
                      //  var_dump($y_json[$ctr]);
                       // echo "---------->>";
                       // var_dump($ctr);
                        $arrX=array();
                        array_push($arrX,$y_json[$ctr]);
                        array_push($obj->input,$neuron->learn($x_json, $y_json,10,$ctr));
                    }

                }
            }   
     }
    }


    public function predict($x_json){
       //steps  ctr epochs will come here and we take the corrosponding index steps..
 
          for ($i=0;$i<sizeof($this->model);$i++){
            $layer=$this->model[$i];
            if($i==0){
              //input layer neurons will take the x_json as input ...
              $arr=array();
              $obj=new stdClass();
              $obj->input=array();
            
              
              for($j=0;$j<sizeof($layer);$j++){
                  array_push($obj->input,$layer[$j]->predict($x_json));
              }
              array_push($arr,$obj);

            }else{

              $obj2=$arr;
              $arr=array();
              $obj=new stdClass();
              $obj->input=array();
              
              for($j=0;$j<sizeof($layer);$j++){
                $neuron=$layer[$j];
                //debug input and output json ... remaining things are okay

                array_push($obj->input,$neuron->predict($obj2));
              }
              array_push($arr,$obj);
            }
       }
      return $arr;
    }
}



$x_json=json_decode( '[
    {"input": [0,0]},
    {"input": [1,0]},
    {"input": [0,0]},
    {"input": [0,1]},
    {"input": [0,0]},
    {"input": [1,1]}
]');

$y_json=json_decode('[
    {"output": 0},
    {"output": 1},
    {"output": 0},
    {"output": 1},
    {"output": 0},
    {"output": 1}
]');

$mat=json_decode('[
    {"input": [0]}
]');

$mat1=json_decode('[
    {"input": [1]}
]');

$mat2=json_decode('[
    {"input": [1]}
]');


$x1_json=json_decode( '[
    {"input": [0,0]}
]');

$x2_json=json_decode( '[
    {"input": [0,0]}
]');

$L1=new Linear_Model();
$L1->add_layer($x_json,0,2,'sigmoid');
$L1->add_layer($x2_json,0,1,'sigmoid');

//Pass train data set...
$L1->train($x_json,$y_json,20);

$check=$L1->predict($mat);
var_dump($check);

$check=$L1->predict($mat1);
var_dump($check);

$check=$L1->predict($mat2);
var_dump($check);
/*

$check=$L1->predict($mat);


   if($check[0][1][0]>0.500){
       echo("Yes");
   }else{
       echo("No");
   }

die($check[0][1][0]);
*/
