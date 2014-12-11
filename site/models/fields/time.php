<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Add CSS and JS
JHtml::stylesheet('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css');
JHtml::stylesheet('http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css');
JHtml::script('http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js');

jimport('joomla.form.formfield');

class JFormFieldDateTime extends JFormField {

    protected $type = 'Time';

    public function getInput() {
            return '<div class="well">'.
                    '<div id="datetimepicker2" class="input-append">'.
                        '<input data-format="HH:mm PP" type="text"></input>'.
                        '<span class="add-on">'.
                          '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>'.
                        '</span>'.
                      '</div>'.
                    '</div>'.
                    '<script type="text/javascript">'.
                      'jQuery(function() {'.
                        'jQuery("#datetimepicker2").datetimepicker({'.
                          'language: "en",'.
                          'pick12HourFormat: true'.
                        '});'.
                      '});'.
                    '</script>';
    }
}