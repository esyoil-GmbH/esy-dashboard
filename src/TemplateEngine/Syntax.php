<?php
/**
 * Created by PhpStorm.
 * User: bennet
 * Date: 18.04.18
 * Time: 20:45
 */

namespace Angle\Engine\Template;


class Syntax {

    public $tokens = array();

    public function __construct() {

        // {:var} & { :var }
        $this->addRule("/{ :([\w\d]+) }/", "<?= $$1; ?>");
        $this->addRule("/{:([\w\d]+)}/", "<?= $$1; ?>");

        // {foreach :entry in :list} & { foreach :entry in :list } & { foreach :entry in :list with :key }
        $this->addRule("/{ foreach :([\w\d]+) in :([\w\d]+) }/", "<?php foreach ($$2 as $$1): ?>");
        $this->addRule("/{foreach :([\w\d]+) in :([\w\d]+)}/", "<?php foreach ($$2 as $$1): ?>");
        $this->addRule("/{ foreach :([\w\d]+) in :([\w\d]+) with :([\w\d]+) }/", "<?php foreach ($$2 as $$3 => $$1): ?>");

        // {endforeach} & { endforeach }
        $this->addRule("/{ endforeach }/", "<?php endforeach; ?>");
        $this->addRule("/{endforeach}/", "<?php endforeach; ?>");
    
        // { :list.point } & {:list.point}
        $this->addRule('/{ :([\w\d]+).(.*) }/', '<?= $$1[\'$2\'] ?>');
    }

    public function addRule($pattern, $replacement) {
        if (is_callable($replacement)) {
            $this->tokens[] = ['pattern' => $pattern, 'replacement' => $replacement, 'callback' => true];
        } else {
            $this->tokens[] = ['pattern' => $pattern, 'replacement' => $replacement, 'callback' => false];
        }
    }

    public function getTokens() {
        return $this->tokens;
    }

}