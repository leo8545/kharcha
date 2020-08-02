<?php


namespace App;


class Form
{
    public static object $data;
    public static object $validatedData;
    private static \App\ValidationMethods $vm;

    public function __construct(array $fields)
    {
        self::$data = (object) $fields;
        self::$validatedData = (object) [];
        self::$vm = new \App\ValidationMethods();
    }

    private function getLimit($haystack, $regex)
    {
        if(preg_match($regex, $haystack) === 1) {
            return (int) explode(":",$haystack)[1];
        }
        return 0;
    }

    public function validate()
    {
        // returns the validated fields with errors (if any)
        foreach( self::$data as $fieldName => $filters ) {
            foreach( $filters as $filter ) {
                $doBreak = false;
                if(strpos($filter, "|break") !== false) {
                    $filter = explode("|", $filter)[0];
                    $doBreak = true;
                }
                switch( $filter ) {
                    case "required":
                        self::$validatedData = self::$vm->validate_required($fieldName);
                        break;
                    case (preg_match("/max:*/", $filter) === 1):
                        $limit = $this->getLimit($filter, "/max:*/");
                        self::$validatedData = self::$vm->validate_maxLength($fieldName, $limit);
                        break;
                    case (preg_match("/min:*/", $filter) === 1):
                        $limit = $this->getLimit($filter, "/min:*/");
                        self::$validatedData = self::$vm->validate_minLength($fieldName, $limit);
                        break;

                }
                if($doBreak) break;
            }
        }
        return self::$validatedData;
    }
}