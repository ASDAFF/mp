<?
Class WS_SaleUserProfilesErrorsContainer{
    public $errors = array();

    function addErrorString($message) {
        $this->errors[] = $message;
        return $this;
    }

    function getErrorsAsString() {
        if (!empty($this->errors)) {
            return implode("\n", $this->errors);
        }
        return false;
    }
}
?>