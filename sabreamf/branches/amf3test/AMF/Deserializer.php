<?php

    class SabreAMF_Deserializer {

        private $stream;
        private $objectcount;
        private $refList;

        public function __construct(SabreAMF_InputStream $stream) {

            $this->stream = $stream;

        }

        public function readAMFData($settype = null) {

           if (is_null($settype)) {
                $settype = $this->stream->readByte();
           }

           echo("Type: " . dechex($settype) . "\n");

           switch ($settype) {

                case SabreAMF_Const::AT_NUMBER      : return $this->stream->readDouble();
                case SabreAMF_Const::AT_BOOL        : return $this->stream->readByte()==true;
                case SabreAMF_Const::AT_STRING      : return $this->stream->readString();
                case SabreAMF_Const::AT_OBJECT      : return $this->readObject();
                case SabreAMF_Const::AT_NULL        : return null; 
                case SabreAMF_Const::AT_UNDEFINED   : return null;
                //case self::AT_REFERENCE   : return $this->readReference();
                case SabreAMF_Const::AT_MIXEDARRAY  : return $this->readMixedArray();
                case SabreAMF_Const::AT_ARRAY       : return $this->readArray();
                case SabreAMF_Const::AT_DATE        : return $this->readDate();
                case SabreAMF_Const::AT_LONGSTRING  : return $this->stream->readLongString();
                case SabreAMF_Const::AT_UNSUPPORTED : return null;
                case SabreAMF_Const::AT_XML         : return $this->stream->readLongString();
                case SabreAMF_Const::AT_TYPEDOBJECT : return $this->readTypedObject();
                case 0x11                           : return $this->testData();
                default                   :  throw new Exception('Unsupported type: 0x' . strtoupper(str_pad(dechex($settype),2,0,STR_PAD_LEFT))); return false;
 
           }

        }

        public function testData() {

            for($i=0; $i<9; $i++) echo($this->stream->readByte());
            echo($this->stream->readString());
            die();

        }

        public function readObject() {

            $object = array();
            while (true) {
                $key = $this->stream->readString();
                $vartype = $this->stream->readByte();
                if ($vartype==SabreAMF_Const::AT_OBJECTTERM) break;
                $object[$key] = $this->readAmfData($vartype);
            }
            return $object;    

        }

        public function readArray() {

            $length = $this->stream->readLong();
            $arr = array();
            while($length--) $arr[] = $this->readAMFData();
            return $arr;

        }

        public function readMixedArray() {

            $highestIndex = $this->stream->readLong();
            return $this->readObject();

        }

        public function readDate() {

            $timestamp = floor($this->stream->readDouble() / 1000);
            $timezoneOffset = $this->stream->readInt();
            if ($timezoneOffset > 720) $timezoneOffset = ((65536 - $timezoneOffset));
            $timezoneOffset=($timezoneOffset * 60) - date('Z');
            return $timestamp + ($timezoneOffset);


        }

        public function readTypedObject() {

            $classname = $this->stream->readString();
            return $this->readObject();

        }

    }

?>