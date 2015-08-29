function getScript(url, success) {
    var script = document.createElement('script');
    script.src = url;
    var head = document.getElementsByTagName('head')[0],
            done = false;
    // Attach handlers for all browsers
    script.onload = script.onreadystatechange = function() {
        if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
            done = true;
            success();
            script.onload = script.onreadystatechange = null;
            head.removeChild(script);
        }
    };
    head.appendChild(script);
}
function setTask(task) {
    document.getElementById('task').value=task;
}

function recv_show(i) {
        var show = document.getElementById('show_'+i);
        var hide = document.getElementById('hide_'+i);
        var nest = document.getElementById('nested_'+i);
        show.style.display = "none";
        hide.style.display = "inline";
        nest.style.display = "block";
}

function recv_hide(i) {
        var show = document.getElementById('show_'+i);
        var hide = document.getElementById('hide_'+i);
        var nest = document.getElementById('nested_'+i);
        show.style.display = "inline";
        hide.style.display = "none";
        nest.style.display = "none";
}