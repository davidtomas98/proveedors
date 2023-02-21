<?php
    namespace App\Controller;
    use App\Config\TextAlign;
    use App\Entity\Proveedor;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use phpDocumentor\Reflection\Types\Boolean;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;



    class ProveedorController extends Controller{
        /**
         * @Route("/", name="proveedor_list")
         * Method({"GET")
         */
        public function index() {
            $proveedores = $this->getDoctrine()->getRepository(Proveedor::class)->findAll();

            return $this->render('proveedors/index.html.twig', array('proveedores' => $proveedores));
        }

        /**
         * @Route("/proveedors/nuevo", name="new_proveedor")
         * Method({"GET", "POST"})
         */
        public function new(Request $request) {
            $proveedor= new Proveedor();

            $form = $this->createFormBuilder($proveedor)
                ->add('nombre', TextType::class)
                ->add('correo', TextType::class)
                ->add('telefono', TextType::class)
                ->add('tipo', ChoiceType::class, [
                    'placeholder' => 'Seleccionar',
                    'choices' => [
                        'Hotel' => 'Hotel',
                        'Pista' => 'Pista',
                        'Complemento' => 'Complemento',
                        ],
                ])
                ->add('activo', CheckboxType::class, [
                    'required' => false
                ])
                ->add('save', SubmitType::class, array(
                    'label' => 'Crear',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $proveedor = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($proveedor);
                $entityManager->flush();

                return $this->redirectToRoute('proveedor_list');
            }

            return $this->render('proveedors/nuevo.html.twig', array(
                'form' => $form->createView()
            ));
        }
        /**
         * @Route("/proveedor/editar/{id}", name="editar_proveedor")
         * Method({"GET", "POST"})
         */
        public function edit(Request $request, $id) {
            $proveedor = new Proveedor();
            $proveedor = $this->getDoctrine()->getRepository(Proveedor::class)->find($id);

            $form = $this->createFormBuilder($proveedor)
                ->add('nombre', TextType::class, array('attr' => array('class' => 'form-control')))
                ->add('correo', TextType::class, array('attr' => array('class' => 'form-control')))
                ->add('telefono', TextType::class, array('attr' => array('class' => 'form-control')))
                ->add('tipo', ChoiceType::class, [
                    'choices' => [
                        'Hotel' => 'Hotel',
                        'Pista' => 'Pista',
                        'Complemento' => 'Complemento',
                    ],
                ])
                ->add('activo', CheckboxType::class, [
                    'label_attr' => ['class' => 'switch-custom'],
                    'required' => false,
                ])
                ->add('save', SubmitType::class, array(
                    'label' => 'Editar',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('proveedor_list');
            }

            return $this->render('proveedors/editar.html.twig', array(
                'form' => $form->createView()
            ));
        }

        /**
         * @Route("/proveedor/{id}", name="proveedor_mostrar")
         */
        public function show($id) {
            $proveedor = $this->getDoctrine()->getRepository(Proveedor::class)->find($id);

            return $this->render('proveedors/mostrar.html.twig', array('proveedor' => $proveedor));
        }

        /**
         * @Route("/proveedor/borrar/{id}")
         * @Method({"DELETE"})
         */
        public function delete(Request $request, $id) {
            $proveedor = $this->getDoctrine()->getRepository(Proveedor::class)->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proveedor);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }

    }