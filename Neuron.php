<?php
/* Create the neuron -by Kaustubh B at March 28 2019 */
error_reporting(E_ERROR | E_PARSE);
//Have a look on line 174 ..any suggesstions will be appreciated...
class Neuron
{

    private $weight;
    private $delta_weight;
    public $neuron_id;
    public $activate;
    private $cost_arr;

    
    function __construct($x_json,$default_weight,$neuron_id,$act) {
        $this->cost_arr=array();
        $this->activate=$act;
        $this->neuron_id=$neuron_id;
        $this->default_weight($x_json,$default_weight);
    }
    
    
    private function nn($arr){


        for($i=0;$i<sizeof($arr);$i++){
            ${"i".($i+1)}=$arr[$i];
            
        }
  
        $ctr=1;
        
        $h_arr=array();
        
        for($i=0;$i<sizeof($arr);$i++){
            
            for($j=0;$j<sizeof($arr);$j++){
                $key="i".($j+1)."_h".$ctr;
                $key2="i".($j+1);
               $sum=$sum+($this->weight->$key*${$key2});
            }
            
            $key="bias_h".($i+1);
            array_push($h_arr,($sum+$this->weight->$key));
            $sum=0;
            $ctr++;
        }


        $sum=0;
        for($i=0;$i<sizeof($h_arr);$i++){
            $key="h".($i+1)."_o1";
            $sum = $this->weight->$key * $this->activation($h_arr[$i]);
        }

        $sum=$sum+$this->weight->bias_o1;
        return $this->activation($sum);
    }

    private function weight_init($key,$value){
        $this->delta_weight->$key=0;
    }
    
    private function delta_weight_init($key,$value){
        $this->weight->$key=$value;
    }

    private function default_weight($x_json,$default_weight){
        $this->weight=new stdClass();
        $this->delta_weight=new stdClass();
        
        $ctr=1;
   
        for($i=0;$i<sizeof($x_json[0]->input);$i++){
            for($j=0;$j<sizeof($x_json[0]->input);$j++){
                    $this->weight_init("i".($j+1)."_h".($i+1), mt_rand(1,5)/10);
                    $this->delta_weight_init("i".($j+1)."_h".($i+1),$default_weight);
            }
            $key="bias_h".($i+1);
            $this->weight->$key=mt_rand(1,5)/10;
            $this->delta_weight->$key=$default_weight;
            $key="h".($i+1)."_o1";
            $this->weight->$key=mt_rand(1,5)/10;
            $this->delta_weight->$key=$default_weight;
            $ctr++;
        }
        $this->weight->bias_o1=mt_rand(1,5)/10;
        $this->delta_weight->bias_o1=$default_weight;

        foreach($x_json as $obj){
            $i1=$obj->input[0];
            $i2=$obj->input[1];
            $this->nn($i1,$i2);
        }

    }
    
    private function activation($sum) {
  
        if($this->activate=="sigmoid"){
          return   $this->sigmoid($sum);
        }else if($this->activate=="relu"){
          return   $this->relu($sum);
        }else{
            throw new Exception("Unknown activation function..");
        }
    }
        
    private function relu($sum){
        //Apply relu activation func..
        $y1=max($sum,0);
       /* if(($sum-$y)*($sum-$y)!=0){
            array_push($this->cost_arr,(1 / ((($sum - $y) * ($sum - $y)) * 0.5)));
        }else{
            array_push($this->cost_arr,0);
        }*/
        return $y1;
    }
    
    
    
    private function derivative_relu($x){
       $fx= $this->relu($x);
        return $fx*(1- $fx);
    }


    private function sigmoid($sum){
        //Apply sigmoid activation func..
        $y1=1/(1+exp(-$sum));
        return $y1;
    }
    
    private function derivative_sigmoid($x){
       $fx= $this->sigmoid($x);
        return $fx*(1- $fx);
    }

 
  
    private function nn_learn($arr,$output){

          for($i=0;$i<sizeof($arr);$i++){
                ${"i".($i+1)}=$arr[$i];
                
            }
              
             for($i=0;$i<sizeof($arr);$i++){
              $sum=0;
              for($j=0;$j<sizeof($arr);$j++){
                    $key="i".($j+1)."_h".($i+1);
                    $key2=${"i".($i+1)};
                    $sum=$sum+$this->weight->$key *  $key2;
              }
                    $key3="bias_h".($i+1);
                    $sum=$sum+$this->weight->$key3;
                    ${"h".($i+1)."_input"}=$sum;
                    ${"h".($i+1)}=$this->activation($sum);
                    $sum=0;
            }
              

              $ar=array();
             for($i=0;$i<sizeof($arr);$i++){
              $key="h".($i+1)."_o1";
              $val="Hthis->weight->H".$key." *"."h".($i+1);
              $val=str_replace("H","$" ,$val);
              array_push ($ar,$val);
             }
             
          array_push($ar,$this->weight->bias_o1);
          

          $t1="return ".implode("+", $ar).";";
           $o1_input =eval($t1);

          //this is your accuracy rate.....
          $o1 = $this->sigmoid($this->weight->bias_o1);
          
          $delta = $output - $o1;

          $o1_delta = $delta * $this->derivative_sigmoid($o1_input);

          for($i=0;$i<sizeof($arr);$i++){
              $key="h".($i+1)."_o1";
              $key2=${"h".($i+1)};
              $this->delta_weight->$key +=  $key2* $o1_delta;
          }

          $this->delta_weight->bias_o1 += $o1_delta;
          $h_delta=array();
          for($i=0;$i<sizeof($arr);$i++){
            $key="h".($i+1)."_input";
            ${"h".($i+1)."_delta"}= $o1_delta * $this->derivative_sigmoid(${$key});
            //check if this we have to take as accuracy or not...
            array_push($h_delta, ${"h".($i+1)."_delta"});
          }
          
          for($i=0;$i<sizeof($arr);$i++){
            for($j=0;$j<sizeof($arr);$j++){
                $key="i".($j+1)."_h".($i+1);
                $key2=${"h".($i+1)."_delta"};
                $this->delta_weight->$key += ${"i".($j+1)} * $key2;
            }
            $bias_h="bias_h".($i+1);
            $this->delta_weight->$bias_h += ${"h".($i+1)."_delta"};
          }
          

      return $h_delta;
    }



    public function learn($x_json,$y_json,$iterations,$predict_index){
       
     for($o=0;$o<$iterations;$o++){

             for($i=0;$i<sizeof($x_json);$i++){

                $delta=$this->nn_learn($x_json[$i]->input,$y_json[$i]->output);
                $delta=array_sum($delta)/count($delta);
                 array_push($this->cost_arr, 0.5*($delta)*($delta));
                

                   foreach ( $this->weight as $key => $value) {
                     $this->weight->$key = $this->weight->$key+$this->delta_weight->$key;
                  }
            }
            array_push($this->cost_arr,$sum/sizeof($x_json)) ;
    }
        

        return $this->predict($x_json[$predict_index]->input);
    }


    public function predict($arr)
    {
        return $this->nn($arr);
    }

    public function plot_training(){
        $file = fopen("training/".$this->neuron_id.".txt", "w") or die("Unable to open file!");
        fwrite($file,json_encode($this->cost_arr));
        fclose($file);

        $url="http://192.168.1.100:8888/graph.html?title=Accuray metrics&id=".$this->neuron_id;
        echo '<script>window.open ("'.$url.'", "Accuracy metrics","status=0,toolbar=0")</script>';
    }

    public function get_image(){
        $json=new stdClass();
        $json->id=$this->neuron_id;
        $json->activation=$this->activation;
        $json->weights=$this->weight;

        return $json;
    }

    public function  get_weight(){
        return $this->weight;
    }

    public function plot_weight(){
        $file = fopen("training/".$this->neuron_id.".txt", "w") or die("Unable to open file!");
        var_dump($this->weight);
        fwrite($file,json_encode($this->weight,true));
        fclose($file);

        $url="http://192.168.1.100:8888/graph.html?title=Generic way to visualize weight&id=".$this->neuron_id;
        echo '<script>window.open ("'.$url.'", "Weight plot","status=0,toolbar=0")</script>';
    }
    
}


$x_json=json_decode( '[
  {"input": [0,1,0,1,0,1,0,1,0,0,1,0,1,0,1]},
  {"input": [1,0,1,0,1,0,1,0,1,0,1,0,1,0,1]},
  {"input": [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]},
  {"input": [0,1,0,1,0,1,0,1,0,1,0,1,0,1,0]},
  {"input": [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]},
  {"input": [1,1,0,1,1,0,1,1,0,1,1,0,1,1,0]}
]');

$y_json=json_decode('[
  {"output": 0},
  {"output": 1},
  {"output": 0},
  {"output": 1},
  {"output": 0},
  {"output": 1}
]');

$mat=json_decode('{
  "input": [2,2,2,2,2,0,0,0,0,0,0,0,0,0,0]
}');

$mat1=json_decode('{
  "input": [1,1,0,1,1,0,1,1,0,1,1,0,1,1,0]
}');

$mat2=json_decode('{
  "input": [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
}');

$n1=new Neuron($x_json,0,1,"sigmoid");

#A epoch..
$n1->learn($x_json,$y_json,120,0);

var_dump("1 ---> ".$n1->predict($mat->input));
var_dump("1 ---> ".$n1->predict($mat1->input));
var_dump("0 ---> ".$n1->predict($mat2->input));

//var_dump($n1->get_image());


$n1->plot_training();
//$n1->plot_weight();
die("");




