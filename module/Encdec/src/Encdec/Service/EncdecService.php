<?php


namespace Encdec\Service;


use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EncdecService implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	protected $transport;
	/**
	 * Set service locator
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}
	
	/**
	 * Get service locator
	 *
	 * @return ServiceLocatorInterface
	*/
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	public function mntencodeAlgo($id)
    {
                
                $id = $id*365;



            $convert = array(
                "0" => 'm2OMhPOs',
                "1" => 'C8Xl2tC6',
                "2" => 'aUOtE4KL',
                "3" => 'LQCD417M',
                "4" => 'vonR2kEr',
                "5" => 'fOjMtLHl',
                "6" => 'HDytvajf',
                "7" => 'fyWrdSdu',
                "8" => 'KIoLfVtl',
                "9" => 'rmugZCbz'
                );
            $str='';
            while($id > 1)
                {

                    $num = $id%10;
                    $id = $id/10;
                    $str=$str.$convert[$num];
                }
                $str = strrev($str);
            return $str;
    }

    public function mntdecodeAlgo($string)
    {
    	$flag=0;
    				$string2=strrev($string);
    				$strchk = strlen($string);
    				if($strchk % 8 == 0){
    				$arr=str_split($string2, 8);

                    $convert = array(
                            "m2OMhPOs" => '0',
                            "C8Xl2tC6" => '1',
                            "aUOtE4KL" => '2',
                            "LQCD417M" => '3',
                            "vonR2kEr" => '4',
                            "fOjMtLHl" => '5',
                            "HDytvajf" => '6',
                            "fyWrdSdu" => '7',
                            "KIoLfVtl" => '8',
                            "rmugZCbz" => '9'
                            );
					foreach ($arr as $ar) {
						if (!array_key_exists($ar,$convert)){
							$id=0; $flag=1;
							break;
						}
						else
						$str[]=$convert[$ar];
						}
						if($flag == 0){
							$str=implode($str);
							$result = strrev($str);
		                    $id = $result;
		                    $id = $id/365;
		                }
                }
                else
                {
                	$id=0;
                }
                    return $id;
    }


}