<?php
$classNodeType = 'class';
$interfaceNodeType = 'interface';
$traitNodeType = 'usecase';


foreach (glob("./*.php") as $filename) {
    echo 'weeee' . $filename;
    include_once $filename;
    echo 'end' . $filename;
} unset ($filename);




// $internalFunctions = array_values($internalFunctions);

// var_export (gettype ($internalFunctions[0]));




function writeConnection($from, $to, $arrow) {
    echo "from, to";
}

$internalClasses = array_values(array_map(fn($name) => new ReflectionClass($name),
    array_filter(
        get_declared_classes(),
        function($className) {
            return !call_user_func(
                array(new ReflectionClass($className), 'isInternal')
            );
        }
    )
));

$internalInterfaces = array_values(array_map(fn($name) => new ReflectionClass($name),
    array_filter(
        get_declared_interfaces(),
        function($interfaceName) {
            return !call_user_func(
                array(new ReflectionClass($interfaceName), 'isInternal')
            );
        }
    ))
);

$internalTraits = array_values(array_map(fn($name) => new ReflectionClass($name),
    array_filter(
        get_declared_traits(),
        function($traitName) {
            return !call_user_func(
                array(new ReflectionClass($traitName), 'isInternal')
            );
        }
    ))
);

function classExtendsClass($from, $to) {
    echo "$from --|> $to\n";
}

function interfaceExtendsInterface($from, $to) {
    echo "$from --|> $to\n";
}

function classUsesTrait($from, $to) {
    echo "$from ..|> $to\n";
}

function classImplementsInterface($from, $to) {
    echo "$from ..|> $to\n";
}


foreach ($internalClasses as $class) {
    // echo("package {$class->getNamespaceName()} {\n");
    echo("$classNodeType " . $class->getShortName() . "\n");
    // echo("}\n");
}

foreach ($internalInterfaces as $interface) {
    // echo("package {$interface->getNamespaceName()} {\n");
    echo("$interfaceNodeType " . $interface->getShortName() . "\n");
    // echo("}\n");
}

foreach ($internalTraits as $trait) {
    // echo("package {$trait->getNamespaceName()} {\n");
    echo("$traitNodeType " . $trait->getShortName() . "\n");
    // echo("}\n");
}

foreach ($internalClasses as $class) {
    $fromName = $class->getShortName();
    foreach ($class->getInterfaceNames() as $implementedInterface) {
        
        $toName = (new ReflectionClass($implementedInterface))->getShortName();
        echo classImplementsInterface($fromName, $toName);
    }
    
    if ($class->getParentClass() != false) {
        classExtendsClass($fromName, (new ReflectionClass($class->get_parent_class()))->getShortName());
    }
}

foreach ($internalInterfaces as $interface) {
    $fromName = $interface->getShortName();
    foreach ($interface->getInterfaceNames() as $implementedInterface) {
        
        $toName = (new ReflectionClass($implementedInterface))->getShortName();
        echo interfaceExtendsInterface($fromName, $toName);
    }
    
    if ($class->getParentClass() != false) {
        classExtendsClass($fromName, (new ReflectionClass($class->get_parent_class()))->getShortName());
    }
}



// var_export($internalTraits);
// var_export([1]->getShortName());
// var_export($internalInterfaces);