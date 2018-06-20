<?php
namespace Psalm;

class location {
    public $start_line;
    public $end_line;
    public $start_offset;
    public $end_offset;
    public $start_column;
    public $end_column;

    public $file_path;
    public $file_name;

    public function __construct($internalLocation) {
        $this->start_line = $internalLocation->getLineNumber();
        $this->end_line = $internalLocation->getEndLineNumber();
        $this->start_column = $internalLocation->getColumn();
        $this->end_column = $internalLocation->getEndColumn();

        list($this->start_offset, $this->end_offset) = $internalLocation->getSelectionBounds();

        $this->file_path = $internalLocation->file_path;
        $this->file_name = $internalLocation->file_name;
    }
}

class CallGraphNode {
    public $definitionLocation;
    public $referenceLocations = [];

    public function __construct($def, $refs) {
        $this->definitionLocation = new location($def);
        foreach ($refs as $refset) {
            // References appear to be group by filepath (TODO: check this)
            foreach ($refset as $ref) {
                $this->referenceLocations[] = new location($ref);
            }
        }
    }

    public function print() {
        // $json = json_encode($this, JSON_UNESCAPED_UNICODE);
        $json = json_encode($this, JSON_PRETTY_PRINT);
        if ($json) {
            print $json . "\r\n";
        } else {
            // TODO: Add logging when a node has been ignored
        }
    }
}
