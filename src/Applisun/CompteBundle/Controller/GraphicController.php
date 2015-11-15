<?php

namespace Applisun\CompteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Applisun\CompteBundle\Highcharts\HighchartCompte;

class GraphicController extends Controller{
    
    public function graphicCompteAction($id, $year)
    {
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplisunCompteBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }
        
        $evolutions = $em->getRepository('ApplisunCompteBundle:Evolution')->getEvolutionByCompteAndYear($id, $year);
        
        $dates = array();
        $data = array();
        if (isset($evolutions))
        {
            foreach ($evolutions as $evolution)
            {
                $dates[] = date('M', $evolution->getCreatedAt()->getTimeStamp());
                $data[] = (float)$evolution->getSolde();
            }
        }
        
        // Chart
        $soldeHistory = array(
            array(
                 "name" => "Solde", 
                 "data" => $data
            ),            
        );

        $ob = new HighchartCompte();
        $ob->chart->renderTo($id.'chart');  
        $ob->title->text('');
        
        $ob->yAxis->title(array('text' => "Solde"));

        $ob->xAxis->title(array('text'  => "Mois"));
        $ob->xAxis->categories($dates);

        $ob->series($soldeHistory);
        
        //first operation of current account
        $firstOpe = $em->getRepository('ApplisunCompteBundle:Operation')->getOperationFirstByCompte($id);

        return $this->render('ApplisunCompteBundle:Graphic:graphicCompte.html.twig', array(
            'linechart' => $ob, 'eltId' => $id.'chart', 'entity' => $entity, 'year' => $year, 'firstYear' => ($firstOpe?date('Y', $firstOpe->getCreatedAt()->getTimeStamp()):date('Y', time()))
        ));
    }
    
    public function graphicOperationAction($month, $year, $compteId)
    {
        $em = $this->getDoctrine()->getManager();
        $operations = $em->getRepository('ApplisunCompteBundle:Operation')->getOperationListForPlot($month, $year, $compteId);
        
        //var_dump($operations); exit();
        
        $data = array();
        if (isset($operations))
        {
            foreach($operations as $operation)
            {
                array_push($data, array($operation['type'], (float)$operation['somme']));                                
            }            
        }
                
        $ob = new HighchartCompte();
        $ob->chart->renderTo($month.'pie');  
        $ob->title->text('');
        $ob->navigation(array("buttonOptions" => array("enabled" => false)));
        
        $data = array(array(
                "type" => "pie",
                "name" => "", 
                "data" => $data,                  
                "center" => array(300, 200),
                "size" => 300,
                "allowPointSelect" => true,
                "cursor" => 'pointer',
                "showInLegend" => false,
                "dataLabels" => array(
                    "enabled" => true,
                    "format" => '{point.name}: {point.y:.1f}€'
                )));
        
        $ob->series($data);
        
        $response = $this->render('ApplisunCompteBundle:Graphic:operation_listForPlot.html.twig', array( 'operations' => $operations, 'piechart' => $ob, 'eltId' => $month.'pie' ));
        
        return $response;
    }
}
