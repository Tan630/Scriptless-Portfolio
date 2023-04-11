
all: ./source/index.html.php
	php ./source/index.html.php > ./index.html
	php ./source/layout.css.php > ./styles/layout.css

index: ./source/index.html.php
	php ./source/index.html.php > ./index.html

style: ./source/layout.css.php 
# Compile layout.css
	php ./source/layout.css.php > ./styles/layout.css
	

doc: ./source/ ./source/lib/docgen.php
	phpdoc ./source/
	php ./source/lib/docgen.php > uml.puml
	puml uml.puml

