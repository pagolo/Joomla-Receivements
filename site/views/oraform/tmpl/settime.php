<?php
/**
 * @version     1.0.5
 * @package     com_receivements
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      Paolo Bozzo - pagolo.bozzo AT gmail.com - http://dbfweb.com
 */
// no direct access
defined('_JEXEC') or die;
?>

  <table style="border:none !important;text-align:center;width:100%;height:100%;font-size:8pt">
  <tr><td><button style="font-size:8pt" onclick="increment('h',1,23)">+</button></td><td><button style="font-size:8pt" onclick="increment('m',5,55)">+</button></td></tr>
  <tr><td><?php echo JText::_('COM_RECEIVEMENTS_HOURS')?><br /><input type="text" size="4" id="h" readonly="readonly" value="08" style="text-align:center;width:30px;font-size:8pt" /></td>
  <td><?php echo JText::_('COM_RECEIVEMENTS_MINUTES')?><br /><input type="text" size="4" readonly="readonly" id="m" value="00" style="text-align:center;width:30px;font-size:8pt" /></td></tr>
  <tr><td><button style="font-size:8pt" onclick="decrement('h',1,23)">-</button></td><td><button style="font-size:8pt" onclick="decrement('m',5,55)">-</button></td></tr>
  </table>
<script type="text/javascript">
var qs = (function(a) {
    if (a == "") return {};
    var b = {};
    for (var i = 0; i < a.length; ++i)
    {
        var p=a[i].split('=', 2);
        if (p.length == 1)
            b[p[0]] = "";
        else
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
    }
    return b;
})(window.location.search.substr(1).split('&'));
var my_parent = window.parent.document.getElementById( qs['parent'] );
var values = my_parent.value.split(":");
var h_id = document.getElementById("h");
var m_id = document.getElementById("m");
h_id.value = values[0];
m_id.value = values[1];
function increment(id, i, maximum) {
        var e = document.getElementById(id);
        var v = parseInt(e.value) + i;
        if (v > maximum) v = 0;
        e.value = v > 9 ? v : '0' + v;
        my_parent.value = h_id.value + ':' + m_id.value + ':00';
}
function decrement(id, i, maximum) {
        var e = document.getElementById(id);
        var v = parseInt(e.value) - i;
        if (v < 0) v = maximum;
        e.value = v > 9 ? v : '0' + v;
        my_parent.value = h_id.value + ':' + m_id.value + ':00';
}
</script>
