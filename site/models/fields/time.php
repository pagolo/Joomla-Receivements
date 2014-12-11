<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Add CSS and JS
if (!isset($_datetime) || !$_datetime) {
    JHtml::stylesheet('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css');
    JHtml::stylesheet('http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css');
    JHtml::script('http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js');
    JHtml::script('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js');
    JHtml::script('http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js');
    $_datetime = true;
}

jimport('joomla.form.formfield');

class JFormFieldTime extends JFormField {

    protected $type = 'Time';

    public function getInput() {
            return '<div class="well">'.
                    '<div id="datetimepicker" class="input-append time">'.
                        '<input type="text"></input>'.
                        '<span class="add-on">'.
                          '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>'.
                        '</span>'.
                      '</div>'.
                    '</div>'.
                    '<script type="text/javascript">'.
                      '$(function() {'.
                        '$("#datetimepicker").datetimepicker({'.
                          'format: \'hh:mm\','.
                          'pickDate: false,'.
                          'pick12HourFormat: false'.
                        '});'.
                      '});'.
                    '</script>';
    }
}