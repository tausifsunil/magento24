<?php

namespace Forever\Core\Block\Css\PreProcessor;

use Magento\Framework\App\State;
use Magento\Framework\Css\PreProcessor\File\Temporary;
use Magento\Framework\Phrase;
use Magento\Framework\View\Asset\ContentProcessorException;
use Magento\Framework\View\Asset\ContentProcessorInterface;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Asset\Source;
use Psr\Log\LoggerInterface;
use Forever\Core\ViewModel\SystemConfigurations;

class Processor extends \Magento\Framework\Css\PreProcessor\Adapter\Less\Processor
{
	const CSS_BACKGROUDCOLOR = 'themedesign/general/backgroundprimary';

	/**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var State
     */
    private $appState;

    /**
     * @var Source
     */
    private $assetSource;

    /**
     * @var Temporary
     */
    private $temporaryFile;

    /**
     * @var SystemConfigurations
     */
    private $systemConfig;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param State $appState
     * @param Source $assetSource
     * @param Temporary $temporaryFile
     * @param SystemConfigurations $systemConfig
     */
    public function __construct(
        LoggerInterface $logger,
        State $appState,
        Source $assetSource,
        Temporary $temporaryFile,
        SystemConfigurations $systemConfig
    ) {
        $this->logger = $logger;
        $this->appState = $appState;
        $this->assetSource = $assetSource;
        $this->temporaryFile = $temporaryFile;
        $this->systemConfig = $systemConfig;
    }

    /**
     * @inheritdoc
     */
    public function processContent(File $asset)
    {
        $path = $asset->getPath();
        $dynamicCss = [];
        try {
            $parser = new \Less_Parser(
                [
                    'relativeUrls' => false,
                    'compress' => $this->appState->getMode() !== State::MODE_DEVELOPER
                ]
            );

            $content = $this->assetSource->getContent($asset);

            if (trim($content) === '') {
                throw new ContentProcessorException(
                    new Phrase('Compilation from source: LESS file is empty: ' . $path)
                );
            }

            $tmpFilePath = $this->temporaryFile->createFile($path, $content);

            gc_disable();
            $parser->parseFile($tmpFilePath, '');
            $dynamicCss['SecondaryColors'] = $this->systemConfig->getconfigValue(self::CSS_BACKGROUDCOLOR);
            $parser->ModifyVars($dynamicCss);
            $content = $parser->getCss();
            gc_enable();

            if (trim($content) === '') {
                throw new ContentProcessorException(
                    new Phrase('Compilation from source: LESS file is empty: ' . $path)
                );
            } else {
                return $content;
            }
        } catch (\Exception $e) {
            throw new ContentProcessorException(new Phrase($e->getMessage()));
        }
    }
}
