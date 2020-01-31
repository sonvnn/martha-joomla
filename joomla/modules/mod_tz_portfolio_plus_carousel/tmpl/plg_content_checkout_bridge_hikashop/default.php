<?php
/*------------------------------------------------------------------------
# plg_extravote - ExtraVote Plugin
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

if (isset($item) && $item) {
    $params_item = new JRegistry($item->attribs);
    $id_product = $params_item->get('purchase_choose_product');
    JLoader::import('content.checkout_bridge_hikashop.models.module', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH);
    $element = PlgTZ_Portfolio_PlusContentCheckout_Bridge_HikashopModelModule::getProduct($id_product, $params);
    if ($element) {
        echo '<div class="tz_switch_hikashop">';
        if ($params->get('show_dimension')) {
            if ($params->get('show_brand') && isset($element->manufacturer_name) && $element->manufacturer_name) {
                echo '<span class="tz_manufacturer">' . JText::_('MANUFACTURER') . ': ' . '<a href="' . $element->manufacturer_link . '">' . $element->manufacturer_name . '</a>';
                echo "<span style='display:none;' itemprop='brand'>" . $element->manufacturer_name . "</span></span><br/>";
            }

            if ($params->get('show_dimension') && bccomp($element->product_width, 0, 3)) {
                ?>
                <span id="hikashop_product_width_main" class="hikashop_product_width_main">
		<?php echo JText::_('PRODUCT_WIDTH') . ': ' . rtrim(rtrim($element->product_width, '0'), ',.') . ' ' . JText::_($element->product_dimension_unit); ?>
                    <br/>
	</span>
                <?php
            }
            if ($params->get('show_dimension') && bccomp($element->product_length, 0, 3)) {
                ?>
                <span id="hikashop_product_length_main" class="hikashop_product_length_main">
		<?php echo JText::_('PRODUCT_LENGTH') . ': ' . rtrim(rtrim($element->product_length, '0'), ',.') . ' ' . JText::_($element->product_dimension_unit); ?>
                    <br/>
	</span>
                <?php
            }
            if ($params->get('show_dimension') && bccomp($element->product_height, 0, 3)) {
                ?>
                <span id="hikashop_product_height_main" class="hikashop_product_height_main">
		<?php echo JText::_('PRODUCT_HEIGHT') . ': ' . rtrim(rtrim($element->product_height, '0'), ',.') . ' ' . JText::_($element->product_dimension_unit); ?>
                    <br/>
	</span>
                <?php
            }
        }
		if(isset($element->prices)){
			if (!empty($element->discount)) {
            if ($params->get('show_discount') == 1) {
                echo '<span class="hikashop_product_discount">' . JText::_('PRICE_DISCOUNT_START');
                if (bccomp($element->discount->discount_flat_amount, 0, 5) !== 0) {
                    echo $element->currencyHelper->format(-1 * $element->discount->discount_flat_amount, $element->prices[0]->price_currency_id);
                } elseif (bccomp($element->discount->discount_percent_amount, 0, 5) !== 0) {
                    echo -1 * $element->discount->discount_percent_amount . '%';
                }
                echo JText::_('PRICE_DISCOUNT_END') . '</span>';
            } elseif ($params->get('show_discount') == 2) {
                echo '<span class="hikashop_product_price_before_discount">' . JText::_('PRICE_DISCOUNT_START');
                if ($params->get('price_with_tax')) {
                    echo $element->currencyHelper->format($element->prices[0]->price_value_without_discount_with_tax, $element->prices[0]->price_currency_id);
                }
                if ($params->get('price_with_tax') == 2) {
                    echo JText::_('PRICE_BEFORE_TAX');
                }
                if ($params->get('price_with_tax') == 2 || !$params->get('price_with_tax')) {
                    echo $element->currencyHelper->format($element->prices[0]->price_value_without_discount, $element->prices[0]->price_currency_id);
                }
                if ($params->get('price_with_tax') == 2) {
                    echo JText::_('PRICE_AFTER_TAX');
                }
                if ($params->get('show_original_price') && !empty($element->prices[0]->price_orig_value_without_discount_with_tax)) {
                    echo JText::_('PRICE_BEFORE_ORIG');
                    if ($params->get('price_with_tax')) {
                        echo $element->currencyHelper->format($element->prices[0]->price_orig_value_without_discount_with_tax, $element->prices[0]->price_orig_currency_id);
                    }
                    if ($params->get('price_with_tax') == 2) {
                        echo JText::_('PRICE_BEFORE_TAX');
                    }
                    if ($params->get('price_with_tax') == 2 || !$params->get('price_with_tax') && !empty($element->prices[0]->price_orig_value_without_discount)) {
                        echo $element->currencyHelper->format($element->prices[0]->price_orig_value_without_discount, $element->prices[0]->price_orig_currency_id);
                    }
                    if ($params->get('price_with_tax') == 2) {
                        echo JText::_('PRICE_AFTER_TAX');
                    }
                    echo JText::_('PRICE_AFTER_ORIG');
                }
                echo JText::_('PRICE_DISCOUNT_END') . '</span><br/>';
            } elseif ($params->get('show_discount') == 3) {
            }
        }

        $attributes = '';
        if (!empty($element->element->product_id) && !@$element->element->displayed_price_microdata) {
            $round = $element->currencyHelper->getRounding($element->prices[0]->price_currency_id, true);
            $element->element->displayed_price_microdata = true;
            if ($params->get('price_with_tax')) {
                $attributes = ' itemprop="price" content="' . str_replace(',', '.', $element->currencyHelper->round($element->prices[0]->price_value_with_tax, $round, 0, true)) . '"';
            } else {
                $attributes = ' itemprop="price" content="' . str_replace(',', '.', $element->currencyHelper->round($element->prices[0]->price_value, $round, 0, true)) . '"';
            }
        }
        echo '<span class=""' . $attributes . '>';

        if ($params->get('price_with_tax')) {
            echo $element->currencyHelper->format(@$element->prices[0]->price_value_with_tax, $element->prices[0]->price_currency_id);
        }
        if ($params->get('price_with_tax') == 2) {
            echo JText::_('PRICE_BEFORE_TAX');
        }
        if ($params->get('price_with_tax') == 2 || !$params->get('price_with_tax')) {
            echo $element->currencyHelper->format(@$element->prices[0]->price_value, $element->prices[0]->price_currency_id);
        }
        if ($params->get('price_with_tax') == 2) {
            echo JText::_('PRICE_AFTER_TAX');
        }
        if ($params->get('show_original_price') && !empty($element->prices[0]->price_orig_value)) {
            echo JText::_('PRICE_BEFORE_ORIG');
            if ($params->get('price_with_tax')) {
                echo $element->currencyHelper->format($element->prices[0]->price_orig_value_with_tax, $element->prices[0]->price_orig_currency_id);
            }
            if ($params->get('price_with_tax') == 2) {
                echo JText::_('PRICE_BEFORE_TAX');
            }
            if ($params->get('price_with_tax') == 2 || !$params->get('price_with_tax')) {
                echo $element->currencyHelper->format($element->prices[0]->price_orig_value, $element->prices[0]->price_orig_currency_id);
            }
            if ($params->get('price_with_tax') == 2) {
                echo JText::_('PRICE_AFTER_TAX');
            }
            echo JText::_('PRICE_AFTER_ORIG');
        }
        echo '</span><br/> ';
		}
        $classical_url = 'product&task=updatecart&add=1&product_id=' . $element->product_id;

        $stock_class = ($element->product_quantity != 0) ? "" : " hikashop_product_no_stock";
        ?>


        <?php
        if ($params->get('show_stock')) {
            echo ' <span class="hikashop_product_stock_count<?php echo $stock_class; ?>">';
            if ($element->product_quantity > 0) {
                echo(($element->product_quantity == 1 && JText::_('X_ITEM_IN_STOCK') != 'X_ITEM_IN_STOCK') ? JText::sprintf('X_ITEM_IN_STOCK', $element->product_quantity) : JText::sprintf('X_ITEMS_IN_STOCK', $element->product_quantity));
            } elseif (!(int)$element->product_quantity == -1 || !(int)$element->product_quantity > 0) {
                echo JText::_('NO_STOCK');
            }
            echo ' </span><br/>';
        }
        ?>


        <a href="<?php echo hikashop_completeLink($classical_url); ?>"
           onclick="if(window.hikashop.addToCart) { return window.hikashop.addToCart(this); }"
           data-addToCart="<?php echo $element->product_id; ?>"
           data-addTo-div=""
           data-addTo-class="add_in_progress"
           class="btn btn-primary"
        >
            <?php echo $params->get('text_button'); ?>
        </a>
    <?php  echo '</div>';}

}