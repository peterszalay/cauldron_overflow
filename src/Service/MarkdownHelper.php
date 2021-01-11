<?php

namespace App\Service;


use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;


class MarkdownHelper
{
    private $markdownParser;
    private $cache;
    private $isDebug;
    private $logger;

    /**
     * MarkdownHelper constructor.
     */
    public function __construct(MarkdownParserInterface $markdownParser, CacheInterface $cache, bool $isDebug, LoggerInterface $logger)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        $this->logger = $logger;
    }

    public function parse(string $source) :string
    {
        if(!$this->isDebug){
            return $this->markdownParser->transformMarkdown($source);
        }
        return $this->cache->get('markdown_'.md5($source), function () use ($source) {
            return $this->markdownParser->transformMarkdown($source);
        });
    }
}