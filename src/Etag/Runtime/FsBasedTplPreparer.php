<?php
/**
 * User: bigbigant
 * Date: Mar 08 2015
 */
namespace Etag\Runtime;

class FsBasedTplPreparer implements TplPreparer
{

    private $_tplDir;

    private $_compiledTplDir;

    public function __construct($config)
    {
        $conf = \Etag\Util\Config::fromArray($config);
        $this->_tplDir = $conf->rstr('tplDir');
        $this->_compiledTplDir = $conf->str('compiledTplDir', null, true);
    }

    /**
     *
     * @see \Etag\Runtime\TplPreparer::prepare()
     */
    public function prepare($name, \Etag\Compiler\Compiler $compiler = null)
    {
        $sourceFile = $this->getSourceFile($name);
        $targetFile = $this->getTargetFile($name);
        $tpl = new FsBasedTpl($sourceFile, $targetFile);
        if ($tpl->checkTarget()) {
            return $targetFile;
        }
        $source = $tpl->loadSource();
        $tpl->writeTarget($compiler->compile($source));
        return $targetFile;
    }
}

