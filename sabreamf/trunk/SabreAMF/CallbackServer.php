<?php

    require_once('SabreAMF/Server.php');
    require_once('SabreAMF/AMF3/AbstractMessage.php');
    require_once('SabreAMF/AMF3/AcknowledgeMessage.php');
    require_once('SabreAMF/AMF3/RemotingMessage.php');
    require_once('SabreAMF/AMF3/CommandMessage.php');
    require_once('SabreAMF/AMF3/ErrorMessage.php');

    /**
     * AMF Server
     * 
     * This is the AMF0/AMF3 Server class. Use this class to construct a gateway for clients to connect to 
     *
     * The difference between this server class and the regular server, is that this server is aware of the
     * AMF3 Messaging system, and there is no need to manually construct the AcknowledgeMessage classes.
     * Also, the response to the ping message will be done for you.
     * 
     * @package SabreAMF 
     * @version $Id$
     * @copyright 2006 Rooftop Solutions
     * @author Evert Pot <evert@collab.nl> 
     * @licence http://www.freebsd.org/copyright/license.html  BSD License (4 Clause)
     * @uses SabreAMF_Server
     * @uses SabreAMF_Message
     * @uses SabreAMF_Const
     */
    class SabreAMF_CallbackServer extends SabreAMF_Server {

        /**
         * onInvokeService
         *
         * @var callback
         */
        public $onInvokeService;

        private function handleCommandMessage(SabreAMF_AMF3_CommandMessage $request) {

            switch($request->operation) {

                case SabreAMF_AMF3_CommandMessage::CLIENT_PING_OPERATION :
                    $response = new SabreAMF_AMF3_AcknowledgeMessage($request);
                    break;
                default :
                    throw new Exception('Unknown CommandMessage operation: '  . $request->operation);

            }
            return $response;

        }

        protected function invokeService($service,$method,$data) {

            if (is_callable($this->onInvokeService)) {
                return call_user_func_array($this->onInvokeService,array($service,$method,$data));
            } else {
                throw new Exception('onInvokeService is not defined or not callable');
            }

        }


        /**
         * exec
         * 
         * @return void
         */
        public function exec() {

            foreach($this->getRequests() as $request) {

                // Default AMFVersion
                $AMFVersion = 0;

                $response = null;

                try {

                    // See if we are dealing with the AMF3 messaging system
                    if (is_object($request['data']) && $request['data'] instanceof SabreAMF_AMF3_AbstractMessage) {
                        
                        $AMFVersion = 3;
                       
                        // See if we are dealing with a CommandMessage
                        if ($request['data'] instanceof SabreAMF_AMF3_CommandMessage) {

                            // Handle the command message 
                            $response = $this->handleCommandMessage($request['data']);
                        }

                        // Is this maybe a RemotingMessage ?
                        if ($request['data'] instanceof SabreAMF_AMF3_RemotingMessage) {

                            // Yes
                            $response = new SabreAMF_AMF3_AcknowledgeMessage($request['data']);
                            $response->body = $this->invokeService($request['data']->source,$request['data']->operation,$request['data']->body);

                        }

                    } else {

                        // We are dealing with AMF0
                        $service = substr($request['target'],0,strrpos($request['target'],'.'));
                        $method  = substr(strrchr($request['target'],'.'),1);
                        
                        $response = $this->invokeService($service,$method,$request['data']);

                    }

                    $status = SabreAMF_Const::R_RESULT;

                } catch (Exception $e) {

                    // We got an exception somewhere, ignore anything that has happened and send back
                    // exception information
                    

                    switch($AMFVersion) {
                        case 0 :
                            $response = array(
                                'description' => $e->getMessage(),
                                'details'     => false,
                                'line'        => $e->getLine(), 
                                'code'        => $e->getCode(),
                            );
                            break;
                        case 3 :
                            $response = new SabreAMF_AMF3_ErrorMessage($request['data']);
                            $response->faultString = $e->getMessage();
                            $response->faultCode   = $e->getCode();
                            break;

                    }
                    $status = SabreAMF_Const::R_STATUS;
                }

                $this->setResponse($request['response'],$status,$response);

            }
            $this->sendResponse();

        }

    }

?>
