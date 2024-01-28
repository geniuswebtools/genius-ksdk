<?php

/**
 * WARNING! YOU SHOULD BE USGIN A SANDBOX ACCOUNT AND NOT A PRODUCTION ACCOUNT
 * FOR THESE TESTS!
 * 
 * Add your Personal Access Token or a Service Account Key below before executing
 * the automated tests in the run.php file.
 * 
 */
return 'YOUR_KEAP_API_KEY_GOES_HERE';

/**
 * DO NOT EDIT BELOW THIS LINE!
 */
function TLDR(string $var) {
    if ($var === 'YOUR_KEAP_API_KEY_GOES_HERE') {
        throw new \Exception('Add the Keap API key from your sandbox account to the key.php file, and try again.');
    }
    if ($var === '') {
        throw new \Exception('Your Keap API key from your sandbox account is missing in the key.php file.');
    }
}

/**
 * A kellicam was a Klingon unit of length measurement. This unit of measurement 
 * was intended for use at a planetary scale, and was insignificant for 
 * interstellar measurement.
 */
class Kellicam {

    protected $results = array(),
            $Qpla = true;

    public function expect(bool $cond, $text, $response) {
        if ($cond === false) {
            $this->Qpla = false;
        }
        $this->results[] = array(
            'status' => (($cond === true) ? 'Passed' : 'Failed'),
            'text' => (($cond === true) ? '' : $text),
            'response' => $response,
        );
    }

    public function results($shiny = true) {
        $data = $this->results;
        if ($shiny === true) {
            $data = '<!DOCTYPE html><head><title>GeniusKSDK Tests</title></head><body>';
            foreach ((array) $this->results as $index => $result) {
                $no = ($index + 1);
                $statusRGB = (($result['status'] === 'Passed') ? 'green' : 'red');
                $status = '<span style="color: ' . $statusRGB . '">' . $result['status'] . '</span>';
                $id = 'result' . $no;
                $message = $result['text'];
                $data .= '<div style="margin-bottom: 2em;">';
                $data .= '<strong>Test #' . $no . '</strong><br />';
                $data .= '<strong>Status:</strong> ' . $status . '<br />';
                if (!empty($message)) {
                    $data .= '<strong>Message:</strong> ' . $message . '<br />';
                }
                $data .= '<a href="#' . $id . '" onclick="(() => {
                        let id = this.getAttribute(\'href\').replace(\'#\', \'\');
                        let res = document.getElementById(id);
                        let state = res.style.display;
                        let toggle = ((state === \'none\') ? \'block\' : \'none\');
                        res.style.display = toggle;
                    })();
                    return false;">Response</a><br />';
                $data .= '<pre id="' . $id . '" style="display: none;">' . $this->verbose($result['response']) . '</pre>';
                $data .= '</div>';
                $data .= '<hr />';
            }
            $data .= '</body></html>';
        }
        return $data;
    }

    public function success() {
        return $this->Qpla;
    }

    public function verbose($var) {
        if (!is_scalar($var)) {
            if ($var === null) {
                return 'null';
            }
            return json_encode($var);
        } else if ($var === true) {
            return 'true';
        } else if ($var === false) {
            return 'false';
        }
        return $var;
    }
}
