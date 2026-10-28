/**
 * UltraAddons - Woo Checkout Widget Script
 *
 * Handles Multi-Step Wizard navigation, inline coupon application,
 * interactive quantity adjustments (+/-), and live AJAX checkout updates.
 *
 * @package UltraAddons
 * @version 3.1.1
 */

(function ($) {
  "use strict";

  var UA_Checkout = {
    init: function ($scope) {
      var $container = $scope.find(".ua-woo-checkout");
      if (!$container.length) {
        $container = $scope.is(".ua-woo-checkout") ? $scope : $();
      }
      if (!$container.length) {
        return;
      }

      var layout = $container.data("layout") || "two-columns";
      if ($scope.hasClass("ua-checkout-layout-one-column") || $container.hasClass("ua-checkout-layout-one-column")) {
        layout = "one-column";
      } else if ($scope.hasClass("ua-checkout-layout-multistep") || $container.hasClass("ua-checkout-layout-multistep")) {
        layout = "multistep";
      } else if ($scope.hasClass("ua-checkout-layout-accordion") || $container.hasClass("ua-checkout-layout-accordion")) {
        layout = "accordion";
      }

      // 1. Ensure DOM normalization for two-columns and one-column
      if (layout === "two-columns" || layout === "one-column") {
        UA_Checkout.normalizeLayout($container);
      }

      // 2. Initialize Multi-Step Wizard
      if (layout === "multistep") {
        UA_Checkout.initMultiStep($container);
      }

      // 3. Initialize Accordion
      if (layout === "accordion") {
        UA_Checkout.initAccordion($container);
      }

      // 4. Initialize Inline Coupon Form
      UA_Checkout.initInlineCoupon($container);

      // 5. Initialize Interactive Quantity Stepper & Removal
      UA_Checkout.initQuantitySteppers($container);

      // 6. Re-bind on WooCommerce AJAX fragment updates
      $(document.body).off("updated_checkout.ua_checkout").on("updated_checkout.ua_checkout", function () {
        if (layout === "two-columns" || layout === "one-column") {
          UA_Checkout.normalizeLayout($container);
        }
        UA_Checkout.initQuantitySteppers($container);
      });
    },

    /**
     * DOM Normalization across themes (Storefront, Woodmart, Astra)
     */
    normalizeLayout: function ($container) {
      var $form = $container.find("form.woocommerce-checkout");
      if (!$form.length) return;

      var layout = $container.data("layout") || "two-columns";
      if (layout === "two-columns" || layout === "one-column") {
        // Fallback for custom themes that bypass standard WooCommerce hooks
        if (!$form.find(".ua-checkout-col-left").length) {
          var $customerDetails = $form.find("#customer_details, .col2-set").first();
          if ($customerDetails.length) {
            $customerDetails.wrap('<div class="ua-checkout-col-left ua-checkout-card"></div>');
          }
        }

        if (!$form.find(".ua-checkout-col-right").length) {
          var $heading = $form.find("#order_review_heading");
          var $review = $form.find(".checkout-order-review, #order_review, .woocommerce-checkout-review-order").first();
          if ($review.length) {
            $heading.add($review).wrapAll('<div class="ua-checkout-col-right"><div class="ua-checkout-review-sticky-inner ua-checkout-card"></div></div>');
          }
        } else {
          // If col-right exists, ensure heading is at the top inside the card
          var $colRight = $form.find(".ua-checkout-col-right");
          var $head = $form.find("#order_review_heading");
          var $card = $colRight.find(".ua-checkout-review-sticky-inner").first();
          if ($head.length && $card.length && !$head.closest($card).length) {
            $card.prepend($head);
          }
        }
      }
    },

    /**
     * Multi-Step Wizard Setup & Navigation
     */
    initMultiStep: function ($container) {
      var $form = $container.find("form.woocommerce-checkout");
      if (!$form.length) {
        return;
      }

      var currentStep = 1;
      var $stepsBar = $container.find(".ua-checkout-multistep-bar");
      var $fill = $stepsBar.find(".ua-step-progress-fill");

      // Wrap sections into Step 1, Step 2, Step 3
      var $billing = $form.find(".woocommerce-billing-fields");
      var $account = $form.find(".woocommerce-account-fields");
      var $shipping = $form.find(".woocommerce-shipping-fields");
      var $additional = $form.find(".woocommerce-additional-fields");
      var $orderReview = $form.find(
        "#order_review_heading, #order_review, .checkout-order-review, .woocommerce-checkout-review-order"
      );

      if (!$form.find(".ua-step-panel").length) {
        // Step 1 Panel
        var $step1 = $(
          '<div class="ua-step-panel ua-step-panel-1 is-active"></div>'
        );
        $billing.add($account).wrapAll($step1);

        // Step 2 Panel
        var $step2 = $(
          '<div class="ua-step-panel ua-step-panel-2" style="display:none;"></div>'
        );
        $shipping.add($additional).wrapAll($step2);

        // Step 3 Panel
        var $step3 = $(
          '<div class="ua-step-panel ua-step-panel-3" style="display:none;"></div>'
        );
        $orderReview.wrapAll($step3);

        // Injected Nav Buttons
        var nextText =
          (window.uaCheckoutConfig &&
            window.uaCheckoutConfig.i18n &&
            window.uaCheckoutConfig.i18n.step_next) ||
          "Continue to Next Step →";
        var prevText =
          (window.uaCheckoutConfig &&
            window.uaCheckoutConfig.i18n &&
            window.uaCheckoutConfig.i18n.step_prev) ||
          "← Back to Previous Step";

        // Step 1 Next Button
        $form.find(".ua-step-panel-1").append(
          '<div class="ua-step-nav-buttons">' +
            "<div></div>" +
            '<button type="button" class="ua-checkout-next-btn" data-next="2">' +
            nextText +
            "</button>" +
            "</div>"
        );

        // Step 2 Nav Buttons
        $form.find(".ua-step-panel-2").append(
          '<div class="ua-step-nav-buttons">' +
            '<button type="button" class="ua-checkout-prev-btn" data-prev="1">' +
            prevText +
            "</button>" +
            '<button type="button" class="ua-checkout-next-btn" data-next="3">' +
            nextText +
            "</button>" +
            "</div>"
        );
      }

      function goToStep(step) {
        if (step < 1 || step > 3) return;

        // Animate panel transition
        $form.find(".ua-step-panel").hide().removeClass("is-active");
        $form
          .find(".ua-step-panel-" + step)
          .fadeIn(200)
          .addClass("is-active");

        // Update progress bar tabs
        $stepsBar
          .find(".ua-checkout-step")
          .removeClass("is-active is-completed");
        for (var i = 1; i < step; i++) {
          $stepsBar
            .find('.ua-checkout-step[data-step="' + i + '"]')
            .addClass("is-completed");
        }
        $stepsBar
          .find('.ua-checkout-step[data-step="' + step + '"]')
          .addClass("is-active");

        // Update progress bar track fill
        var percentage = step === 1 ? 33 : step === 2 ? 66 : 100;
        $fill.css("width", percentage + "%");

        currentStep = step;

        // Scroll to top of checkout container
        $("html, body").animate(
          {
            scrollTop: $container.offset().top - 80,
          },
          300
        );
      }

      function validateStep(step) {
        var $panel = $form.find(".ua-step-panel-" + step);
        var isValid = true;
        var $firstError = null;

        $panel
          .find(
            "p.validate-required input, p.validate-required select, input[required]"
          )
          .each(function () {
            var $input = $(this);
            var val = $.trim($input.val());

            // If empty
            if (val === "") {
              isValid = false;
              $input.addClass("ua-input-error");
              if (!$firstError) {
                $firstError = $input;
              }
            } else {
              $input.removeClass("ua-input-error");
            }

            // Email validation
            if ($input.attr("type") === "email" && val !== "") {
              var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
              if (!emailRegex.test(val)) {
                isValid = false;
                $input.addClass("ua-input-error");
                if (!$firstError) {
                  $firstError = $input;
                }
              }
            }
          });

        if (!isValid && $firstError) {
          $firstError.focus();
          var errorMsg =
            (window.uaCheckoutConfig &&
              window.uaCheckoutConfig.i18n &&
              window.uaCheckoutConfig.i18n.step_error) ||
            "Please complete all required fields.";
          if (!$container.find(".ua-step-error-banner").length) {
            $panel.prepend(
              '<div class="ua-step-error-banner ua-alert ua-alert-danger" style="background:#fee2e2; border:1px solid #f87171; color:#b91c1c; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px;">' +
                errorMsg +
                "</div>"
            );
          }
        } else {
          $container.find(".ua-step-error-banner").remove();
        }

        return isValid;
      }

      // Next Button Click
      $form.on("click", ".ua-checkout-next-btn", function (e) {
        e.preventDefault();
        var next = parseInt($(this).data("next"), 10);
        var validate = validateStep(next - 1);
        if (validate) {
          goToStep(next);
        }
      });

      // Prev Button Click
      $form.on("click", ".ua-checkout-prev-btn", function (e) {
        e.preventDefault();
        var prev = parseInt($(this).data("prev"), 10);
        goToStep(prev);
      });

      // Click on Progress Tab
      $stepsBar.on("click", ".ua-checkout-step", function () {
        var targetStep = parseInt($(this).data("step"), 10);
        // Only allow clicking to earlier steps or if current step is valid
        if (targetStep < currentStep) {
          goToStep(targetStep);
        } else if (targetStep > currentStep && validateStep(currentStep)) {
          goToStep(targetStep);
        }
      });
    },

    /**
     * Accordion Layout Setup & Interaction
     */
    initAccordion: function ($container) {
      var $form = $container.find("form.woocommerce-checkout");
      if (!$form.length) return;

      if ($form.find(".ua-accordion-item").length) return;

      var $billing = $form.find(".woocommerce-billing-fields, .woocommerce-account-fields");
      var $shipping = $form.find(".woocommerce-shipping-fields, .woocommerce-additional-fields");
      var $orderReview = $form.find("#order_review_heading, #order_review, .checkout-order-review, .woocommerce-checkout-review-order");

      // Wrap Section 1: Billing
      var $item1 = $('<div class="ua-accordion-item ua-accordion-item-1 is-open"></div>');
      $item1.append('<div class="ua-accordion-header"><span class="ua-acc-badge">1</span><span class="ua-acc-title">Billing Details</span><span class="ua-acc-toggle">▾</span></div>');
      var $body1 = $('<div class="ua-accordion-body"></div>');
      $billing.wrapAll($body1);
      $item1.append($billing.parent());
      $form.prepend($item1);

      // Wrap Section 2: Shipping & Additional
      var $item2 = $('<div class="ua-accordion-item ua-accordion-item-2"></div>');
      $item2.append('<div class="ua-accordion-header"><span class="ua-acc-badge">2</span><span class="ua-acc-title">Shipping & Order Notes</span><span class="ua-acc-toggle">▾</span></div>');
      var $body2 = $('<div class="ua-accordion-body" style="display:none;"></div>');
      $shipping.wrapAll($body2);
      $item2.append($shipping.parent());
      $item1.after($item2);

      // Wrap Section 3: Review & Payment
      var $item3 = $('<div class="ua-accordion-item ua-accordion-item-3"></div>');
      $item3.append('<div class="ua-accordion-header"><span class="ua-acc-badge">3</span><span class="ua-acc-title">Order Summary & Payment</span><span class="ua-acc-toggle">▾</span></div>');
      var $body3 = $('<div class="ua-accordion-body" style="display:none;"></div>');
      $orderReview.wrapAll($body3);
      $item3.append($orderReview.parent());
      $item2.after($item3);

      // Accordion toggle click handler
      $form.on("click", ".ua-accordion-header", function (e) {
        e.preventDefault();
        var $currentItem = $(this).closest(".ua-accordion-item");
        var $currentBody = $currentItem.find(".ua-accordion-body");
        if ($currentItem.hasClass("is-open")) {
          $currentBody.slideUp(200);
          $currentItem.removeClass("is-open");
        } else {
          $form.find(".ua-accordion-item.is-open .ua-accordion-body").slideUp(200);
          $form.find(".ua-accordion-item").removeClass("is-open");
          $currentBody.slideDown(200);
          $currentItem.addClass("is-open");
        }
      });
    },

    /**
     * Inline Coupon Form Application
     */
    initInlineCoupon: function ($container) {
      $container.on("click", ".ua-checkout-apply-coupon-btn", function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $box = $btn.closest(".ua-checkout-inline-coupon-box");
        var $input = $box.find(".ua-coupon-code-input");
        var $msg = $box.find(".ua-coupon-ajax-msg");
        var code = $.trim($input.val());

        if (!code) {
          $input.focus();
          return;
        }

        $btn
          .prop("disabled", true)
          .text(
            (window.uaCheckoutConfig &&
              window.uaCheckoutConfig.i18n &&
              window.uaCheckoutConfig.i18n.applying) ||
              "Applying..."
          );
        $msg.removeClass("is-success is-error").hide();

        var nonce =
          window.wc_checkout_params &&
          window.wc_checkout_params.apply_coupon_nonce
            ? window.wc_checkout_params.apply_coupon_nonce
            : window.uaCheckoutConfig
            ? window.uaCheckoutConfig.nonce
            : "";

        var endpoint =
          window.uaCheckoutConfig && window.uaCheckoutConfig.wc_ajax_url
            ? window.uaCheckoutConfig.wc_ajax_url.replace(
                "%%endpoint%%",
                "apply_coupon"
              )
            : "/?wc-ajax=apply_coupon";

        $.ajax({
          type: "POST",
          url: endpoint,
          data: {
            security: nonce,
            coupon_code: code,
          },
          dataType: "html",
          success: function (response) {
            $btn.prop("disabled", false).text("Apply");
            $input.val("");

            // Parse response message from WooCommerce HTML
            var $resp = $("<div>" + response + "</div>");
            var isError = $resp.find(".woocommerce-error").length > 0;
            var text = $resp.text().trim();

            if (isError) {
              $msg.addClass("is-error").text(text).fadeIn(150);
            } else {
              $msg
                .addClass("is-success")
                .text(text || "Coupon applied successfully!")
                .fadeIn(150);
            }

            // Refresh checkout table
            $(document.body).trigger("update_checkout");
          },
          error: function () {
            $btn.prop("disabled", false).text("Apply");
            $msg
              .addClass("is-error")
              .text("An error occurred while applying coupon.")
              .fadeIn(150);
          },
        });
      });

      // Press Enter in coupon input
      $container.on("keypress", ".ua-coupon-code-input", function (e) {
        if (e.which === 13) {
          e.preventDefault();
          $(this).siblings(".ua-checkout-apply-coupon-btn").trigger("click");
        }
      });
    },

    /**
     * Interactive Quantity Stepper (+/-) & Item Removal
     */
    initQuantitySteppers: function ($container) {
      // Plus button
      $container.off("click", ".ua-qty-plus").on("click", ".ua-qty-plus", function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $input = $btn.siblings(".ua-qty-input");
        var currentVal = parseInt($input.val(), 10) || 1;
        var newVal = currentVal + 1;
        var key = $input.data("key");

        $input.val(newVal);
        UA_Checkout.updateCartQuantity(key, newVal, $container);
      });

      // Minus button
      $container.off("click", ".ua-qty-minus").on("click", ".ua-qty-minus", function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $input = $btn.siblings(".ua-qty-input");
        var currentVal = parseInt($input.val(), 10) || 1;
        if (currentVal <= 1) return;
        var newVal = currentVal - 1;
        var key = $input.data("key");

        $input.val(newVal);
        UA_Checkout.updateCartQuantity(key, newVal, $container);
      });

      // Remove item
      $container.off("click", ".ua-checkout-remove-item").on("click", ".ua-checkout-remove-item", function (e) {
        e.preventDefault();
        var key = $(this).data("key");
        var confirmMsg =
          (window.uaCheckoutConfig &&
            window.uaCheckoutConfig.i18n &&
            window.uaCheckoutConfig.i18n.confirm_remove) ||
          "Remove this item?";
        if (window.confirm(confirmMsg)) {
          UA_Checkout.updateCartQuantity(key, 0, $container);
        }
      });
    },

    /**
     * Send AJAX request to update cart quantity
     */
    updateCartQuantity: function (cartItemKey, qty, $container) {
      if (!cartItemKey) return;

      $container.addClass("ua-loading-overlay");

      $.ajax({
        type: "POST",
        url:
          (window.uaCheckoutConfig && window.uaCheckoutConfig.ajax_url) ||
          "/wp-admin/admin-ajax.php",
        data: {
          action: "ua_checkout_update_cart",
          nonce:
            (window.uaCheckoutConfig && window.uaCheckoutConfig.nonce) || "",
          cart_item_key: cartItemKey,
          quantity: qty,
        },
        dataType: "json",
        success: function (response) {
          $container.removeClass("ua-loading-overlay");
          if (response && response.success) {
            if (response.data && response.data.is_empty) {
              window.location.reload();
            } else {
              $(document.body).trigger("update_checkout");
            }
          }
        },
        error: function () {
          $container.removeClass("ua-loading-overlay");
          $(document.body).trigger("update_checkout");
        },
      });
    },
  };

  // Elementor Frontend Hook
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/ultraaddons-woo-checkout.default",
      UA_Checkout.init
    );
  });

  // Ready fallback for live site and preview frame
  $(document).ready(function () {
    $(".ua-woo-checkout").each(function () {
      UA_Checkout.init($(this));
    });
  });
})(jQuery);
