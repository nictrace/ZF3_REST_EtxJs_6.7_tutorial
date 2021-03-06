<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repos$
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Interop\Container\ContainerInterface;
use Application\Service\TestService;
use Zend\Diactoros\Response\JsonResponse;
use Zend\View\Model\JsonModel;
use Zend\Http\Headers;
use Zend\Db\Adapter\Adapter;
use Zend\Json\Json;

class LevelController extends AbstractRestfulController
{
        private $db;
        private $container;
        private $em;
        private $repo;

        public function __construct(ContainerInterface $object){
            $this->container = $object;
            $this->em = $this->container->get('doctrine.entitymanager.orm_default');
            $this->repo = $this->em->getRepository(\Application\Entity\Level::class);
            $this->db = new Adapter($this->container->get('Config')['db']);
        }

    public function getList()
    {
	$data = $this->repo->findAll();
	$total = [];
	foreach($data as &$lev){
	    $one = [];
	    if(is_object($lev)){
		$one['id'] = $lev->getId();
		$one['name'] = $lev->getName();
		$m = $lev->getPrivs()->slice(0);	// взять все данные
		$u = [];
		foreach($m as $priv){
		    array_push($u, $priv->getName());
		}
		$one['privileges'] = $u;
		array_push($total,$one);
	    }
	}
        $this->response->getHeaders()->addHeaders(array('Access-Control-Allow-Origin' => '*', 'Content-Type'=>'application/json'));
        $this->response->setContent(\json_encode($total));
	return $this->response;
    }
    public function get($id){
	$data = $this->repo->findOneById($id);
	$total = [];
	$total['id'] = $data->getId();
	$total['name'] = $data->getName();
	$priv = [];
	$m = $data->getPrivs()->slice(0);
        foreach($m as $p){
            array_push($priv, $p->getName());
        }
	$total['privileges'] = $priv;
        $this->response->getHeaders()->addHeaders(array('Access-Control-Allow-Origin' => '*', 'Content-Type'=>'application/json'));
        $this->response->setContent(\json_encode($total));
        return $this->response;
    }
}
