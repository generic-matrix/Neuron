# Neuron
This is a neuron made using PHP .
the linear.php will be made as a linear model , I encourage you to fork it .
I have even attached canvasJS to get to visualize weights and the training accuracy in each neuron.

Refer the functions :

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



#New neuron 
$n1=new Neuron($x_json,0,1,"sigmoid");


$n1->learn($x_json,$y_json,120,0);

var_dump("1 ---> ".$n1->predict($mat->input));
var_dump("1 ---> ".$n1->predict($mat1->input));
var_dump("0 ---> ".$n1->predict($mat2->input));

#We can get the weight's in the form of JSON ..very essencial for reinforcement learning.
var_dump($n1->get_image());


$n1->plot_training();

$n1->plot_weight();

ISSUE:
1) In neuron.php have a look at line 174 
2)Linear model is not working as expected due to logical error for now.

POTENTIAL:
1)We can use the linear.php for reinforement learning.
2)We can implement diffrent realtime models.
3)Will implement LTSM and CNN .
