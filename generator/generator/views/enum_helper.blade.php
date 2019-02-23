<?= '<?php' ?>

<?php
/**
 * @var \nxtlvlsoftware\generator\enums\generator\models\EnumClass[] $classes
 */
?>

/** @noinspection ALL */
// @formatter:off

/**
* A helper file for php-enums, to provide autocomplete information to your IDE.
* Generated on {{ date("Y-m-d H:i:s") }}.
*
* This file should not be included in your code, only analyzed by your IDE!
*
* @see https://github.com/NxtLvLSoftware/php-enums
*/

namespace {
    die("This file should not be included, only analyzed by your IDE!");
}

<?php foreach($classes as $class): ?>
namespace {{ $class->namespace() }} {
    class {{ $class->class() }} {
<?php foreach($class->enums() as $enum): ?>
        /**
         * @return mixed
         */
        public static function {{ $enum->name() }}() {}
<?php endforeach; ?>
    }
}
<?php endforeach; ?>