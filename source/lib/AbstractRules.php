<?php
namespace OCSSDOM\Abstract {
    interface CSSRule { // s
        function getCSSText(): string;
        function getParentRule(): ?CSSRule;
        function getParentStylesheet(): ?CSSStyleSheet;
        function setCssText(string $cssText): void;
        // function getType() : int; // deprecated
    }

    interface CSSMediaRule {
        public function deleteRule(int $index): void;
        public function getCssRules(): ?CSSRuleList;
        public function getmedia(): ?MediaList;
        public function insertRule(String $rule, int $index): int;
    }

    interface CSSRuleList { // w
        public function getLength(): int;
        public function item(int $index): ?CSSRule;
    }

    interface MediaList {
        public function appendMedium(string $newMedium): void;
        public function deleteMedium(string $oldMedium): void;
        public function getLength(int $getLength): int;
        public function getMediaText(): string;
        public function item(int $index): string;
        public function setMediaText(String $mediaText): void;
    }

    interface CSSStyleSheet {// s
        public function deleteRule(int $index): void;
        public function getCssRules(): CSSRuleList;
        public function getOwnerRule(): CSSRule;
        public function insertRule(String $rule, int $index): int;
    }
}

