<?php

class PdfGenerator
{
    private $pages = [];
    private $currentPage = 0;
    private $marginLeft = 40;
    private $marginTop = 40;
    private $marginBottom = 40;
    private $pageWidth = 595.28;
    private $pageHeight = 841.89;
    private $currentY;

    public function __construct($title = 'Relatório')
    {
        $this->addPage();
        $this->addText($title, 16, true);
        $this->addLineBreak(6);
    }

    public function addPage()
    {
        $this->pages[] = [];
        $this->currentPage = count($this->pages) - 1;
        $this->currentY = $this->pageHeight - $this->marginTop;
    }

    public function addText($text, $size = 11, $bold = false)
    {
        if ($text === null || $text === '') {
            return;
        }

        $lineHeight = max($size * 1.2, 12);
        if ($this->currentY - $lineHeight < $this->marginBottom) {
            $this->addPage();
        }

        $lines = explode("\n", (string) $text);
        foreach ($lines as $line) {
            if (trim($line) === '' && $this->currentY - $lineHeight < $this->marginBottom) {
                $this->addPage();
            }

            $this->currentY -= $lineHeight;
            $this->pages[$this->currentPage][] = [
                'text' => trim($line),
                'size' => $size,
                'bold' => $bold,
                'y' => $this->currentY,
                'x' => $this->marginLeft,
            ];
        }
    }

    public function addLineBreak($height = 4)
    {
        $this->currentY -= $height;
        if ($this->currentY < $this->marginBottom) {
            $this->addPage();
        }
    }

    public function addSection($title)
    {
        $this->addText($title, 13, true);
        $this->addLineBreak(1);
        $this->addHorizontalRule();
        $this->addLineBreak(2);
    }

    public function addHeader($title, $subtitle = '')
    {
        $this->addText($title, 16, true);
        if ($subtitle !== '') {
            $this->addText($subtitle, 10);
        }
        $this->addLineBreak(2);
        $this->addHorizontalRule();
        $this->addLineBreak(4);
    }

    public function addTable($headers, $rows)
    {
        $this->addText(implode(' | ', $headers), 10, true);
        $this->addLineBreak(1);
        $this->addHorizontalRule();
        $this->addLineBreak(1);

        foreach ($rows as $row) {
            $this->addText(implode(' | ', $row), 10);
        }

        $this->addLineBreak(4);
    }

    public function addHorizontalRule()
    {
        if ($this->currentY - 6 < $this->marginBottom) {
            $this->addPage();
        }

        $this->pages[$this->currentPage][] = [
            'text' => '________________________________________________________________________________',
            'size' => 8,
            'bold' => false,
            'y' => $this->currentY - 2,
            'x' => $this->marginLeft,
        ];
    }

    public function output($filename = 'relatorio.pdf')
    {
        $pdf = $this->buildPdf();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        echo $pdf;
    }

    public function saveToFile($path)
    {
        return file_put_contents($path, $this->buildPdf()) !== false;
    }

    private function buildPdf()
    {
        $objects = [];
        $pageObjects = [];
        $objectId = 1;
        $catalogId = $objectId++;
        $pagesId = $objectId++;
        $fontId = $objectId++;

        $objects[$catalogId] = "$catalogId 0 obj << /Type /Catalog /Pages $pagesId 0 R >> endobj";
        $pageKids = [];

        foreach ($this->pages as $index => $entries) {
            $contentId = $objectId++;
            $pageId = $objectId++;
            $pageKids[] = "$pageId 0 R";

            $stream = $this->buildPageStream($entries);
            $objects[$contentId] = "$contentId 0 obj << /Length " . strlen($stream) . " >> stream\n$stream\nendstream endobj";
            $objects[$pageId] = "$pageId 0 obj << /Type /Page /Parent $pagesId 0 R /MediaBox [0 0 595.28 841.89] /Contents $contentId 0 R /Resources << /Font << /F1 $fontId 0 R >> >> >> endobj";
        }

        $objects[$pagesId] = "$pagesId 0 obj << /Type /Pages /Kids [" . implode(' ', $pageKids) . "] /Count " . count($pageKids) . " >> endobj";
        $objects[$fontId] = "$fontId 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj";

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        $offset = strlen($pdf);

        foreach ($objects as $id => $content) {
            $offsets[$id] = $offset;
            $pdf .= $content . "\n";
            $offset = strlen($pdf);
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($objects as $id => $content) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$id]);
        }

        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n$xrefPosition\n%%EOF";

        return $pdf;
    }

    private function buildPageStream($entries)
    {
        $stream = '';
        foreach ($entries as $entry) {
            $escapedText = $this->escapePdfText($entry['text']);
            $stream .= "BT\n/F1 {$entry['size']} Tf\n{$entry['x']} {$entry['y']} Td\n($escapedText) Tj\nET\n";
        }

        return $stream;
    }

    private function escapePdfText($text)
    {
        $text = iconv('UTF-8', 'CP1252//TRANSLIT', $text);
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace('(', '\\(', $text);
        $text = str_replace(')', '\\)', $text);
        return $text;
    }
}
