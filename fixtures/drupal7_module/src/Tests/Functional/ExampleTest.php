<?php

namespace Drupal\{{ machine_name }}\Tests\Functional;

final class {{ test_name }} extends \DrupalWebTestCase {

  public static function getInfo() {
    return array(
      'name' => '{{ name }}',
      'description' => '{{ name }} tests.',
      'group' => '{{ name }}',
    );
  }

  public function test_that_the_front_page_loads() {
    $this->drupalGet('<front>');

    $this->assertResponse(200);
  }

}
