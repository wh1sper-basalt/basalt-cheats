<?php
/**
 * My Child Theme — код практик 1–5.
 * Все кастомные хуки WooCommerce и bbPress собраны здесь.
 */

// =================================================================
// ПР1: подключение стилей родителя и дочерней темы
// =================================================================
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri(),
        ['parent-style']
    );
});

// =================================================================
// ПР3: цена вариативного товара в формате «от X»
// =================================================================
add_filter('woocommerce_variable_price_html', 'custom_variation_price_format', 10, 2);
add_filter('woocommerce_variable_sale_price_html', 'custom_variation_price_format', 10, 2);

function custom_variation_price_format($price, $product) {
    $min_price = $product->get_variation_price('min', true);
    $max_price = $product->get_variation_price('max', true);

    if ($min_price == $max_price) {
        return wc_price($min_price);
    }
    return sprintf(__('от %s', 'woocommerce'), wc_price($min_price));
}

// =================================================================
// ПР3 (доп): текст бейджа распродажи «ВЫГОДА!»
// =================================================================
add_filter('woocommerce_sale_flash', 'custom_sale_badge_text', 10, 3);

function custom_sale_badge_text($html, $post, $product) {
    return '<span class="onsale">ВЫГОДА!</span>';
}

// =================================================================
// ПР3 (доп): предупреждение о низком остатке (< 3 шт.)
// =================================================================
add_action('woocommerce_single_product_summary', 'show_low_stock_warning', 15);

function show_low_stock_warning() {
    global $product;
    if (!$product || !$product->managing_stock()) return;

    $qty = $product->get_stock_quantity();
    if ($qty !== null && $qty > 0 && $qty < 3) {
        echo '<p style="color:#dc2626;font-weight:bold;">'
            . 'Успейте купить! Осталось всего ' . (int) $qty . ' шт.'
            . '</p>';
    }
}

// =================================================================
// ПР4: кастомное поле «Ник в Telegram» на checkout
// =================================================================
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

function custom_override_checkout_fields($fields) {
    $fields['billing']['billing_tg_nick'] = [
        'label'       => __('Ник в Telegram', 'woocommerce'),
        'placeholder' => _x('@username', 'placeholder', 'woocommerce'),
        'required'    => true,
        'class'       => ['form-row-wide'],
        'clear'       => true,
        'priority'    => 25,
    ];
    return $fields;
}

// Сохранение значения в мета заказа
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta($order_id) {
    if (!empty($_POST['billing_tg_nick'])) {
        update_post_meta(
            $order_id,
            '_billing_tg_nick',
            sanitize_text_field($_POST['billing_tg_nick'])
        );
    }
}

// Вывод поля в админке заказа
add_action(
    'woocommerce_admin_order_data_after_billing_address',
    'custom_checkout_field_display_admin_order_meta',
    10, 1
);

function custom_checkout_field_display_admin_order_meta($order) {
    $tg = get_post_meta($order->get_id(), '_billing_tg_nick', true);
    if ($tg) {
        echo '<p><strong>' . __('Telegram Nickname', 'woocommerce') . ':</strong> '
            . esc_html($tg) . '</p>';
    }
}

// Вывод поля в PDF-инвойсе (плагин WooCommerce PDF Invoices & Packing Slips)
add_action('wpo_wcpdf_after_billing_address', 'custom_wcpdf_telegram_field', 10, 2);

function custom_wcpdf_telegram_field($template_type, $order) {
    if ($template_type === 'invoice') {
        $tg = get_post_meta($order->get_id(), '_billing_tg_nick', true);
        if ($tg) {
            echo 'Telegram: ' . esc_html($tg) . '<br>';
        }
    }
}

// =================================================================
// ПР4 (сценарий 1): чекбокс «Не звоните мне» + красная метка
// =================================================================
add_filter('woocommerce_checkout_fields', 'custom_no_call_checkbox');

function custom_no_call_checkbox($fields) {
    $fields['billing']['billing_no_call'] = [
        'type'     => 'checkbox',
        'label'    => __('Не звоните мне', 'woocommerce'),
        'required' => false,
        'class'    => ['form-row-wide'],
        'priority' => 26,
    ];
    return $fields;
}

add_action('woocommerce_checkout_update_order_meta', 'custom_save_no_call');

function custom_save_no_call($order_id) {
    $val = !empty($_POST['billing_no_call']) ? '1' : '0';
    update_post_meta($order_id, '_billing_no_call', $val);
}

// Красная метка в админке заказа
add_action('woocommerce_admin_order_data_after_order_details', 'custom_admin_no_call_warning');

function custom_admin_no_call_warning($order) {
    if (get_post_meta($order->get_id(), '_billing_no_call', true) === '1') {
        echo '<p style="color:#dc2626;font-weight:bold;">СРОЧНО: НЕ ЗВОНИТЬ</p>';
    }
}

// =================================================================
// ПР4 (сценарий 2): скрыть курьера, если в корзине есть хрупкий товар
// =================================================================
add_filter('woocommerce_package_rates', 'hide_courier_for_fragile', 10, 2);

function hide_courier_for_fragile($rates, $package) {
    $has_fragile = false;
    foreach ($package['contents'] as $item) {
        if (has_term('fragile', 'product_cat', $item['product_id'])) {
            $has_fragile = true;
            break;
        }
    }
    if (!$has_fragile) return $rates;

    foreach ($rates as $rate_id => $rate) {
        $method = $rate->get_method_id();
        // Удали все варианты flat_rate (курьер), оставив local_pickup
        if (strpos($method, 'flat_rate') !== false) {
            unset($rates[$rate_id]);
        }
    }
    return $rates;
}

// =================================================================
// ПР5: кастомное обязательное поле «Версия окружения» в bbPress
// =================================================================
add_action('bbp_theme_before_topic_form_submit_wrapper', 'custom_bbp_add_environment_field');

function custom_bbp_add_environment_field() {
    $env_value = isset($_POST['bbp_custom_env'])
        ? sanitize_text_field($_POST['bbp_custom_env'])
        : '';
    ?>
    <p>
        <label for="bbp_custom_env">Версия окружения / ПО (обязательно):</label><br />
        <input type="text" id="bbp_custom_env" size="40" name="bbp_custom_env"
               value="<?php echo esc_attr($env_value); ?>"
               placeholder="Например: PHP 8.2 / WordPress 6.4" />
    </p>
    <?php
}

// Валидация: если поле пустое — ошибка и тема не создаётся
add_action('bbp_new_topic_pre_extras', 'custom_bbp_validate_environment_field');

function custom_bbp_validate_environment_field($forum_id) {
    if (empty($_POST['bbp_custom_env'])) {
        bbp_add_error('bbp_topic_env', __('ОШИБКА: Укажите версию окружения!', 'bbpress'));
    }
}

// Сохранение в postmeta темы
add_action('bbp_new_topic_post_extras', 'custom_bbp_save_environment_field', 10, 1);

function custom_bbp_save_environment_field($topic_id) {
    if (!empty($_POST['bbp_custom_env'])) {
        $env = sanitize_text_field($_POST['bbp_custom_env']);
        update_post_meta($topic_id, '_bbp_custom_env', $env);
    }
}

// Вывод метаданных в начале первой публикации темы
add_action('bbp_theme_before_topic_content', 'custom_bbp_display_environment_field');

function custom_bbp_display_environment_field() {
    $topic_id = bbp_get_topic_id();
    $env = get_post_meta($topic_id, '_bbp_custom_env', true);
    if ($env) {
        echo '<div style="background:#f5f5f5;padding:8px 12px;margin-bottom:12px;'
            . 'border-left:3px solid #1a1a1a;font-size:0.85em;">'
            . '<strong>Среда / ПО:</strong> ' . esc_html($env)
            . '</div>';
    }
}