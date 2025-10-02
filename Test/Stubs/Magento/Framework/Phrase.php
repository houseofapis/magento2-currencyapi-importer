<?php
declare(strict_types=1);

namespace Magento\Framework;

class Phrase
{
    private string $text;
    private array $arguments;

    public function __construct(string $text, array $arguments = [])
    {
        $this->text = $text;
        $this->arguments = $arguments;
    }

    public function render(): string
    {
        if (empty($this->arguments)) {
            return $this->text;
        }
        
        // Simple placeholder replacement for testing
        $result = $this->text;
        foreach ($this->arguments as $i => $arg) {
            $result = str_replace('%' . ($i + 1), $arg, $result);
        }
        return $result;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
