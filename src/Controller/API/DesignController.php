<?php


namespace App\Controller\API;


use App\Entity\ProfileDesignSettings;
use App\Repository\ProfileDesignSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DesignController extends AbstractController
{
    private $defValues = [
        'w1' => [
            's1' => '',
            's2' => '',
            's3' => '',
            's4' => '',
            's5' => '',
            's6' => '',
            's7' => '',
            's8' => '',
            's9' => '',
            'c1' => 'off',
            'c2' => 'off',
            'c3' => 'off',
            'c4' => 'off',
            'c5' => 'off',
            'c6' => 'off',
            'b1' => 'off',
        ],
        'w2' => [
            's1' => '',
            's2' => '',
            's3' => '',
            's4' => '',
            'c1' => '',
            'c2' => '',
            'b1' => '',
        ],
        'w3' => [
            's1' => '',
            's2' => '',
            's3' => '',
            's4' => '',
            'c1' => '',
            'c2' => '',
            'b1' => '',
        ]
    ];

    /**
     * @Route("api/changeprofiledesigncontroller", name="changeProfileDesignSettingsApi")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function changeProfileDesignSettings(Request $request, ProfileDesignSettingsRepository $pdsr,
                                                EntityManagerInterface $em){
        $csrf_token = $request->request->get('csrf_token');
        $me = $this->getUser();
        if ($this->isCsrfTokenValid('design_form', $csrf_token)) {
            $profileDesign = $pdsr -> find($me);

            $defValues = $this->defValues;

            if($profileDesign==null) {
                $profileDesign = new ProfileDesignSettings();
                $profileDesign->setAccount($me);
                $profileDesign->setSettings($defValues);
                $newValues = $defValues;
            }else{
                $newValues = $profileDesign->getSettings();
            }

            $reqvalue = [];
            $profilecolor = [];
            foreach($request->request->get('values') as $values){
                $profilecolor[$values['name']] = $values['value'];
            }
            $reqvalue['csrf']=$request->request->get('csrf_token');
            $reqvalue['form_name']=$request->request->get('form_name');

//            if(isset($profilecolor['_reset']) and $profilecolor['_reset']=="on"){
//                if($reqvalue['form_name'] == 'w1'){
//                    $newValues['w1'] = $defValues['w1'];
//                }else if($reqvalue['form_name'] == 'w2'){
//                    $newValues['w2'] = $defValues['w2'];
//                }else if($reqvalue['form_name'] == 'w3'){
//                    $newValues['w3'] = $defValues['w3'];
//                }
//            }else{
                if($reqvalue['form_name'] == 'w1'){
                    $newValues['w1']['s1'] = $profilecolor['s1'];
                    $newValues['w1']['s2'] = $profilecolor['s2'];
                    $newValues['w1']['s3'] = $profilecolor['s3'];
                    $newValues['w1']['s4'] = $profilecolor['s4'];
                    $newValues['w1']['s5'] = $profilecolor['s5'];
                    $newValues['w1']['s6'] = $profilecolor['s6'];
                    $newValues['w1']['s7'] = $profilecolor['s7'];
                    $newValues['w1']['s8'] = $profilecolor['s8'];
                    $newValues['w1']['s9'] = $profilecolor['s9'];

                        if (isset($profilecolor['c1']) and $profilecolor['c1'] == 'on'){
                            $newValues['w1']['c1']=$profilecolor['c1'];
                        }else{
                            $newValues['w1']['c1']='off';
                        }
                        if (isset($profilecolor['c2']) and $profilecolor['c2'] == 'on'){
                            $newValues['w1']['c2']=$profilecolor['c2'];
                        }else{
                            $newValues['w1']['c2']='off';
                        }
                        if (isset($profilecolor['c3']) and $profilecolor['c3'] == 'on'){
                            $newValues['w1']['c3']=$profilecolor['c3'];
                        }else{
                            $newValues['w1']['c3']='off';
                        }
                        if (isset($profilecolor['c4']) and $profilecolor['c4'] == 'on'){
                            $newValues['w1']['c4']=$profilecolor['c4'];
                        }else{
                            $newValues['w1']['c4']='off';
                        }
                        if (isset($profilecolor['c5']) and $profilecolor['c5'] == 'on'){
                            $newValues['w1']['c5']=$profilecolor['c5'];
                        }else{
                            $newValues['w1']['c5']='off';
                        }
                        if (isset($profilecolor['c6']) and $profilecolor['c6'] == 'on') {
                            $newValues['w1']['c6']=$profilecolor['c6'];
                        }else{
                            $newValues['w1']['c6']='off';
                        }
                }else if($reqvalue['form_name'] == 'w2'){

                    $newValues['w2']['s1'] = $profilecolor['s1'];
                    $newValues['w2']['s2'] = $profilecolor['s2'];
                    $newValues['w2']['s3'] = $profilecolor['s3'];
                    $newValues['w2']['s4'] = $profilecolor['s4'];

                    if (isset($profilecolor['c1']) and $profilecolor['c1'] == 'on'){
                        $newValues['w2']['c1']=$profilecolor['c1'];
                    }else{
                        $newValues['w2']['c1']='off';
                    }
                    if (isset($profilecolor['c2']) and $profilecolor['c2'] == 'on'){
                        $newValues['w2']['c2']=$profilecolor['c2'];
                    }else{
                        $newValues['w2']['c2']='off';
                    }

                }else if($reqvalue['form_name'] == 'w3'){

                    $newValues['w3']['s1'] = $profilecolor['s1'];
                    $newValues['w3']['s2'] = $profilecolor['s2'];
                    $newValues['w3']['s3'] = $profilecolor['s3'];
                    $newValues['w3']['s4'] = $profilecolor['s4'];

                    if (isset($profilecolor['c1']) and $profilecolor['c1'] == 'on'){
                        $newValues['w3']['c1']=$profilecolor['c1'];
                    }else{
                        $newValues['w3']['c1']='off';
                    }
                    if (isset($profilecolor['c2']) and $profilecolor['c2'] == 'on'){
                        $newValues['w3']['c2']=$profilecolor['c2'];
                    }else{
                        $newValues['w3']['c2']='off';
                    }

                }
//            }


            $profileDesign->setSettings($newValues);

            $em->persist($profileDesign);
            $em->flush();

            if($request->request->get("form_name" == 'window1design')){
                $request->request->get('dataetc');

            }
        }else{
            return new Response('bad token');
        }
        return new JsonResponse(['success'=>'true', 'time'=>date('H:i:s', time()), $newValues]);
    }

    /**
     * @Route("api/changeprofiledesigncontrollerReset", name="changeProfileDesignSettingsApiReset")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function changeProfileDesignSettingsReset(Request $request, ProfileDesignSettingsRepository $pdsr,
                                                EntityManagerInterface $em)
    {
        $csrf_token = $request->request->get('csrf_token');
        $me = $this->getUser();
        if ($this->isCsrfTokenValid('design_form_reset', $csrf_token)) {
            $profileDesign = $pdsr->find($me);

            if($profileDesign==null) {
                $profileDesign = new ProfileDesignSettings();
                $profileDesign->setAccount($me);
                $profileDesign->setSettings($this->defValues);
                $newValues = $this->defValues;
            }else{
                $newValues = $profileDesign->getSettings();
            }

            $reset = $request->request->get('_reset');
            if($reset and $reset == 'reset_w1'){
                $newValues['w1'] = $this->defValues['w1'];
            }else if($reset and $reset == 'reset_w2'){
                $newValues['w2'] = $this->defValues['w2'];
            }else if($reset and $reset == 'reset_w3'){
                $newValues['w3'] = $this->defValues['w3'];
            }


            $profileDesign->setSettings($newValues);

            $em->persist($profileDesign);
            $em->flush();

        }else{
            return new Response("bad token");
        }
        //dd($reset);
        return $this->redirectToRoute('app_main_profile');
    }
}