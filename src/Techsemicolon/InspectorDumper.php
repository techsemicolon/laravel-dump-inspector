<?php

namespace Techsemicolon;

use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class InspectorDumper extends HtmlDumper
{
    /**
     * Dumps the HTML header.
     */
    protected function getDumpHeader()
    {
        $this->headerIsDumped = true;

        if (null !== $this->dumpHeader) {
            return $this->dumpHeader;
        }

        $line = <<<'EOHTML'
<script>
dumper = window.dumper || function(){
    var tsTrigger = document.createElement('div');
    tsTrigger.className = 'ts_trigger';
    tsTrigger.textContent = 'Open Laravel Dumper';

    var tsContainer = document.createElement('div');
    tsContainer.className = 'ts_dumper_container';
    tsContainer.style.display = 'none';
    var tsHeader = document.createElement('div');
    tsHeader.className = 'ts_dumper_header';
    tsHeader.textContent = 'Laravel Dumper';
    var tsClose = document.createElement('span');
    tsClose.className = 'ts_dumper_close';
    tsClose.textContent = 'X';
    tsHeader.appendChild(tsClose);

    var tsCollapse = document.createElement('span');
    tsCollapse.className = 'ts_dumper_collapse';
    tsCollapse.textContent = 'Collapse All';
    tsHeader.appendChild(tsCollapse);

    var tsExpand = document.createElement('span');
    tsExpand.className = 'ts_dumper_expand';
    tsExpand.textContent = 'Expand All';
    tsHeader.appendChild(tsExpand);

    tsContainer.appendChild(tsHeader);
    var tsBody = document.createElement('div');
    tsBody.className = 'ts_dumper_body';

    Array.prototype.forEach.call(document.querySelectorAll('.sf-dump'), function(c){
        tsBody.appendChild(c);
    });
    tsContainer.appendChild(tsBody);
    document.body.appendChild(tsContainer);
    document.body.appendChild(tsTrigger);
    tsTrigger.addEventListener('click', function(){
        tsTrigger.style.display = 'none';
        tsContainer.style.display = 'block';
        
    });
    tsClose.addEventListener('click', function(){
        tsContainer.style.display = 'none';
        tsTrigger.style.display = 'block';
    });
    tsExpand.addEventListener('click', function(){
        Array.prototype.forEach.call(document.querySelectorAll('.sf-dump-compact'), function(c){
        c.classList.remove('sf-dump-compact');
        c.classList.add('sf-dump-expanded');
        
        });
    });
    tsCollapse.addEventListener('click', function(){
        Array.prototype.forEach.call(document.querySelectorAll('.sf-dump-expanded'), function(c){
        c.classList.remove('sf-dump-expanded');
        c.classList.add('sf-dump-compact');
        
        });
    });
};

Sfdump = window.Sfdump || (function (doc) {

var refStyle = doc.createElement('style'),
    rxEsc = /([.*+?^${}()|\[\]\/\\])/g,
    idRx = /\bsf-dump-\d+-ref[012]\w+\b/,
    keyHint = 0 <= navigator.platform.toUpperCase().indexOf('MAC') ? 'Cmd' : 'Ctrl',
    addEventListener = function (e, n, cb) {
        e.addEventListener(n, cb, false);
    };

(doc.documentElement.firstElementChild || doc.documentElement.children[0]).appendChild(refStyle);

if (!doc.addEventListener) {
    addEventListener = function (element, eventName, callback) {
        element.attachEvent('on' + eventName, function (e) {
            e.preventDefault = function () {e.returnValue = false;};
            e.target = e.srcElement;
            callback(e);
        });
    };
}

function toggle(a, recursive) {
    var s = a.nextSibling || {}, oldClass = s.className, arrow, newClass;

    if ('sf-dump-compact' == oldClass) {
        arrow = '▼';
        newClass = 'sf-dump-expanded';
    } else if ('sf-dump-expanded' == oldClass) {
        arrow = '▶';
        newClass = 'sf-dump-compact';
    } else {
        return false;
    }

    a.lastChild.innerHTML = arrow;
    s.className = newClass;

    if (recursive) {
        try {
            a = s.querySelectorAll('.'+oldClass);
            for (s = 0; s < a.length; ++s) {
                if (a[s].className !== newClass) {
                    a[s].className = newClass;
                    a[s].previousSibling.lastChild.innerHTML = arrow;
                }
            }
        } catch (e) {
        }
    }

    return true;
};

return function (root) {
    root = doc.getElementById(root);

    function a(e, f) {
        addEventListener(root, e, function (e) {
            if ('A' == e.target.tagName) {
                f(e.target, e);
            } else if ('A' == e.target.parentNode.tagName) {
                f(e.target.parentNode, e);
            }
        });
    };
    function isCtrlKey(e) {
        return e.ctrlKey || e.metaKey;
    }
    addEventListener(root, 'mouseover', function (e) {
        if ('' != refStyle.innerHTML) {
            refStyle.innerHTML = '';
        }
    });
    a('mouseover', function (a) {
        if (a = idRx.exec(a.className)) {
            try {
                refStyle.innerHTML = 'pre.sf-dump .'+a[0]+'{background-color: #B729D9; color: #FFF !important; border-radius: 2px}';
            } catch (e) {
            }
        }
    });
    a('click', function (a, e) {
        if (/\bsf-dump-toggle\b/.test(a.className)) {
            e.preventDefault();
            if (!toggle(a, isCtrlKey(e))) {
                var r = doc.getElementById(a.getAttribute('href').substr(1)),
                    s = r.previousSibling,
                    f = r.parentNode,
                    t = a.parentNode;
                t.replaceChild(r, a);
                f.replaceChild(a, s);
                t.insertBefore(s, r);
                f = f.firstChild.nodeValue.match(indentRx);
                t = t.firstChild.nodeValue.match(indentRx);
                if (f && t && f[0] !== t[0]) {
                    r.innerHTML = r.innerHTML.replace(new RegExp('^'+f[0].replace(rxEsc, '\\$1'), 'mg'), t[0]);
                }
                if ('sf-dump-compact' == r.className) {
                    toggle(s, isCtrlKey(e));
                }
            }

            if (doc.getSelection) {
                try {
                    doc.getSelection().removeAllRanges();
                } catch (e) {
                    doc.getSelection().empty();
                }
            } else {
                doc.selection.empty();
            }
        }
    });

    var indentRx = new RegExp('^('+(root.getAttribute('data-indent-pad') || '  ').replace(rxEsc, '\\$1')+')+', 'm'),
        elt = root.getElementsByTagName('A'),
        len = elt.length,
        i = 0,
        t = [];

    while (i < len) t.push(elt[i++]);

    elt = root.getElementsByTagName('SAMP');
    len = elt.length;
    i = 0;

    while (i < len) t.push(elt[i++]);

    root = t;
    len = t.length;
    i = t = 0;

    while (i < len) {
        elt = root[i];
        if ("SAMP" == elt.tagName) {
            elt.className = "sf-dump-expanded";
            a = elt.previousSibling || {};
            if ('A' != a.tagName) {
                a = doc.createElement('A');
                a.className = 'sf-dump-ref';
                elt.parentNode.insertBefore(a, elt);
            } else {
                a.innerHTML += ' ';
            }
            a.title = (a.title ? a.title+'\n[' : '[')+keyHint+'+click] Expand all children';
            a.innerHTML += '<span>▼</span>';
            a.className += ' sf-dump-toggle';
            if ('sf-dump' != elt.parentNode.className) {
                toggle(a);
            }
        } else if ("sf-dump-ref" == elt.className && (a = elt.getAttribute('href'))) {
            a = a.substr(1);
            elt.className += ' '+a;

            if (/[\[{]$/.test(elt.previousSibling.nodeValue)) {
                a = a != elt.nextSibling.id && doc.getElementById(a);
                try {
                    t = a.nextSibling;
                    elt.appendChild(a);
                    t.parentNode.insertBefore(a, t);
                    if (/^[@#]/.test(elt.innerHTML)) {
                        elt.innerHTML += ' <span>▶</span>';
                    } else {
                        elt.innerHTML = '<span>▶</span>';
                        elt.className = 'sf-dump-ref';
                    }
                    elt.className += ' sf-dump-toggle';
                } catch (e) {
                    if ('&' == elt.innerHTML.charAt(0)) {
                        elt.innerHTML = '…';
                        elt.className = 'sf-dump-ref';
                    }
                }
            }
        }
        ++i;
    }
    dumper();
};

})(document);
</script>
<style>
pre.sf-dump {
    display: block;
    white-space: pre;
    padding: 5px;
}
pre.sf-dump span {
    display: inline;
}
pre.sf-dump .sf-dump-compact {
    display: none;
}
pre.sf-dump abbr {
    text-decoration: none;
    border: none;
    cursor: help;
}
pre.sf-dump a {
    text-decoration: none;
    cursor: pointer;
    border: 0;
    outline: none;
}
.ts_dumper_container {
    position: fixed; height: 600px; bottom: 0; z-index: 99999; width: 100%; background: #fff;
}
.ts_dumper_header {
    background: #f8f8f8; height: 40px; line-height: 40px; text-align: center; font-size: 16px; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;
}
.ts_dumper_close {
    position: absolute; right: 15px; top: 0px; cursor: pointer;
}
.ts_dumper_close:hover,
.ts_dumper_expand:hover,
.ts_dumper_collapse:hover {
    color : #000; }
.ts_dumper_body {
    overflow: auto; max-height: 560px;
}
.ts_trigger {
    position: fixed; top: 0; right: 0; z-index: 999999; padding: 10px; background: #19161c; border: 1px solid #ccc; color: #fb8b00; cursor: pointer;
}
.ts_trigger:hover {
    color : #11a0dc;
}
.ts_dumper_collapse {
    position: absolute; right: 40px; top : 0; font-size: 14px; border-right: 1px solid #ccc; padding: 0 15px; cursor: pointer;
}
.ts_dumper_expand {
    position: absolute; right: 140px; top: 0; font-size: 14px; border-right: 1px solid #ccc; padding: 0 15px; cursor: pointer;
}
EOHTML;

        foreach ($this->styles as $class => $style) {
            $line .= 'pre.sf-dump'.('default' !== $class ? ' .sf-dump-'.$class : '').'{'.$style.'}';
        }

        return $this->dumpHeader = preg_replace('/\s+/', ' ', $line).'</style>'.$this->dumpHeader;
    }

}