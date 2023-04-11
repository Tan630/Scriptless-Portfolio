<?php
namespace OCSSDOM\Concrete {
    include_once 'AbstractRules.php';
    include_once 'Indent.php';
    use OCSSDOM\Abstract\CSSRule;
    use OCSSDOM\Abstract\CSSStyleSheet;
    use OCSSDOM\Abstract\CSSMediaRule;
    use OCSSDOM\Abstract\MediaList;
    use OCSSDOM\Abstract\CSSRuleList;
    use OCSSDOM\Tools\Output;

    class ConcreteCSSRule implements CSSRule {
        public function __construct(
            private string $cssText,
            private ?CSSRule $parentRule = null,
            private ?CSSStyleSheet $parentStyleSheet = null
        ) {}

        function getCSSText(): string {
            return $this->cssText;
        }
        function getParentRule(): ?CSSRule {
            return $this->parentRule;
        }
        function getParentStylesheet(): ?CSSStyleSheet {
            return $this->parentStyleSheet;
        }
        function setCssText(string $cssText): void {
            $this->cssText = $cssText;
        }
        function __toString(): string {
            return $this->getCssText();
        }
    }



    class ConcreteCSSRuleList implements CSSRuleList {
        public function __construct(
            private array $rules
        ) {}

        public function getLength(): int {
            return count($this->rules);
        }
        
        public function item(int $index): ?CSSRule {
            if (isset($this->rules[$index])) {
                return $this->rules[$index];
            } else {
                return null;
            }
        }

        public function __toString(): string {
            $result = '';
            foreach ($this->rules as $rule) {
                $result .= $rule;
                $result .= "\n";
            }
            return $result;
        }
    }

    class ConcreteCSSStyleSheet implements CSSStyleSheet {
        private array $rules;
        private ?CSSRule $ownerRule;

        public function __construct(?CSSRule $ownerRule = null) {
            $this->rules = [];
            $this->ownerRule = $ownerRule;
        }

        public function deleteRule(int $index): void{
            $this->rules = deleteAtIndex($this->rules, index);
        }
        public function getCssRules(): CSSRuleList {
            return new ConcreteCSSRuleList($this->rules);
        }
        public function getOwnerRule(): CSSRule {
            return $this->ownerRule;
        }

        public function insertRule(string $rule, int $index): int{
            array_splice($this->rules, $index, 0, $rule );
            return array_search($rule, $this->rules);
        }
        private static function deleteAtIndex(array $arr, int $index) {
            return array_merge(array_slice($arr, 0, $index), array_slice($arr, $index+1));
        }

        public function __toString(): string {
            $result = "";
            $result .= "<style>\n";
            foreach($this->rules as $rule) {
                $result .= ($rule . "\n");
            }
            $result .= "</style>";
            return $result;  
        }
    }

    /**
     * The `ConcreteCSSMediaRule` offers a partial implementation of the CSSMediaRule interface. 
     */
    class ConcreteCSSMediaRule implements CSSMediaRule {
        private string $name;
        private array $rules;
        private MediaList $medias;

        public function __construct() {
            $this->rules = [];
            $this->medias = new ConcreteMediaList();
            $this->name = "@media";
        }

        public function deleteRule(int $index): void{
            $this->rules = deleteAtIndex($this->rules, index);
        }
        public function getCssRules(): CSSRuleList {
            return new ConcreteCSSRuleList($this->rules);
        }

        public function getMedia(): ?MediaList {
            return $this->medias;
        }

        public function insertRule(string $rule, int $index): int {
            array_splice($this->rules, $index, 0, $rule );
            return array_search($rule, $this->rules);
        }

        public function __toString(): string {
            $result = "";
            $result .= ($this->name . '(' . $this->getMedia()->getMediaText() . ')' . " {\n");
    
            $result .= $this->getCSSRules()->__toString();
            $result .= "}\n";
            return  $result;
        }

        
    } 

    
    class ConcreteMediaList implements MediaList{
        // use ImplementArrayAccessByArray;
        public int $what = 1;
        private array $medias;
    
        public function __construct() {
            $this->medias = [];
        }
    
        public function appendMedium(string $newMedium): void {
            array_push($this->medias, $newMedium);
        }
    
        public function deleteMedium(string $oldMedium): void {
            $key = array_search($oldMedium, $this->medias);
            if ($key !== false) {
                unset($this->medias[$key]);
            }
        }

        public function getLength(int $getLength): int {
            return count($this->medias);
        }

        public function getMediaText(): string {
            $aggResult = "";
            foreach ($this->medias as $value) {
                $aggResult .= '' . $value . '';
                $aggResult .= " and ";
            }
            return  substr($aggResult, 0, strlen($aggResult)-5);
        }

        public function setMediaText(String $mediaText): void {
            $this->medias = [$mediaText];
        }

        public function __toString(): string {
            return $this->getMediaText();
        }

        public function item(int $index): string {
            $mediumHit = "";
            $mediaCount = 0;
            
            foreach ($medias as $currentMedia) {
                if ($mediaCount === $index) {
                    return $currentMedia;
                } else {
                    $mediaCount += 1;
                }
            }
            echo 'something is wrong';
        }
    }

    
    


}