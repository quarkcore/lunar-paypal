<div>
    {{--PayPal Button--}}
    <div id="paypal-button-container" class="paypal-button-container"></div>

    {{--Separator--}}
    <div class="relative flex py-5 items-center">
        <div class="flex-grow border-t border-gray-400"></div>
        <span class="flex-shrink mx-4 text-gray-400">or</span>
        <div class="flex-grow border-t border-gray-400"></div>
    </div>

    {{--Card Form--}}
    <div class="card_container">
        <form id="card-form">
            <div>
                <div class="mt-6 grid grid-cols-4 gap-y-6 gap-x-4">
                    <div class="col-span-4">
                        <label for="card-number" class="block text-sm font-medium text-gray-700">Card number</label>
                        <div class="mt-1">
                            <input
                                type="text"
                                id="card-number"
                                name="card-number"
                                autocomplete="cc-number"
                                placeholder="1111 1111 1111 1111"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="col-span-4">
                        <label for="name-on-card" class="block text-sm font-medium text-gray-700">Name on card</label>
                        <div class="mt-1">
                            <input type="text" id="name-on-card" name="name-on-card" autocomplete="cc-name"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="col-span-3">
                        <label for="expiration-date" class="block text-sm font-medium text-gray-700">Expiration date
                            (MM/YY)</label>
                        <div class="mt-1">
                            <input type="text" name="expiration-date" id="expiration-date" autocomplete="cc-exp"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                        <div class="mt-1">
                            <input
                                type="text"
                                name="cvc"
                                id="cvc"
                                autocomplete="csc"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>
            <button
                type="submit"
                class="flex items-center my-4 px-5 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-500 disabled:opacity-50">
                Submit
            </button>
        </form>
    </div>

    {{--Input Masking--}}
    <script>
        let cardNumber = new Cleave('#card-number', {
            creditCard: true,
            onCreditCardTypeChanged: function (type) {
                cvc.destroy()
                cvc = formatCvc(type)
            }
        });

        let expDate = new Cleave('#expiration-date', {
            date: true,
            datePattern: ['m', 'y']
        });

        function formatCvc(type = 'visa') {
            return new Cleave('#cvc', {
                numeral: true,
                numeralIntegerScale: type === 'amex' ? 4 : 3,
                numeralDecimalScale: 0,
                delimiter: '',
                numeralPositiveOnly: true,
                stripLeadingZeroes: false
            });
        }

        let cvc = formatCvc();

    </script>


    {{--PayPal Button(s)--}}
    <script>
        paypal.Buttons({
            // Order is created on the server and the order id is returned
            createOrder() {
                return fetch("/my-server/create-paypal-order", {
                    method: "post",
                    // use the "body" param to optionally pass additional order information
                    // like product skus and quantities
                    body: JSON.stringify({
                        cart: [
                            {
                                sku: "YOUR_PRODUCT_STOCK_KEEPING_UNIT",
                                quantity: "YOUR_PRODUCT_QUANTITY",
                            },
                        ],
                    }),
                })
                .then((response) => response.json())
                .then((order) => order.id);
            },
            // Finalize the transaction on the server after payer approval
            onApprove(data) {
                return fetch("/my-server/capture-paypal-order", {
                    method: "post",
                    body: JSON.stringify({
                        orderID: data.orderID
                    })
                })
                .then((response) => response.json())
                .then((orderData) => {
                    // Successful capture! For dev/demo purposes:
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    const transaction = orderData.purchase_units[0].payments.captures[0];
                    alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                    // When ready to go live, remove the alert and show a success message within this page. For example:
                    // const element = document.getElementById('paypal-button-container');
                    // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                    // Or go to another URL:  window.location.href = 'thank_you.html';
                });
            }
        }).render('#paypal-button-container');
    </script>

    {{--PayPal Hosted Fields--}}
    <script>
        console.log(paypal.HostedFields.isEligible())

        // If this returns false or the card fields aren't visible, see Step #1.
        if (paypal.HostedFields.isEligible()) {
            let orderId

            // Renders card fields
            paypal.HostedFields.render({
                // Call your server to set up the transaction
                createOrder: () => {
                    console.log('createOrder')
                    return fetch('/api/orders', {
                        method: 'post',
                        // use the "body" param to optionally pass additional order information like
                        // product ids or amount.
                    }).then((res) => res.json()).then((orderData) => {
                        orderId = orderData.id // needed later to complete capture
                        return orderData.id
                    })
                },
                fields: {
                    number: {
                        selector: '#card-number',
                    },
                    cvv: {
                        selector: '#cvc',
                    },
                    expirationDate: {
                        selector: '#expiration-date',
                    },
                },
            }).then((cardFields) => {
                document.querySelector('#card-form').addEventListener('submit', (event) => {
                    event.preventDefault()
                    cardFields.submit({
                        // Cardholder's first and last name
                        cardholderName: document.getElementById('name-on-card').value,
                        // Billing Address
                        billingAddress: {
                            // Street address, line 1
                            streetAddress: document.getElementById(
                                'card-billing-address-street',
                            ).value,
                            // Street address, line 2 (Ex: Unit, Apartment, etc.)
                            extendedAddress: document.getElementById(
                                'card-billing-address-unit',
                            ).value,
                            // State
                            region: document.getElementById('card-billing-address-state').value,
                            // City
                            locality: document.getElementById('card-billing-address-city').value,
                            // Postal Code
                            postalCode: document.getElementById('card-billing-address-zip').value,
                            // Country Code
                            countryCodeAlpha2: document.getElementById(
                                'card-billing-address-country',
                            ).value,
                        },
                    }).then(() => {
                        console.log('capturing payment')
                        fetch(`/api/orders/${orderId}/capture`, {
                            method: 'post',
                        }).then((res) => res.json()).then((orderData) => {
                            // Two cases to handle:
                            //   (1) Non-recoverable errors -> Show a failure message
                            //   (2) Successful transaction -> Show confirmation or thank you
                            // This example reads a v2/checkout/orders capture response, propagated from the server
                            // You could use a different API or structure for your 'orderData'
                            var errorDetail =
                                Array.isArray(orderData.details) && orderData.details[0]
                            if (errorDetail) {
                                var msg = 'Sorry, your transaction could not be processed.'
                                if (errorDetail.description)
                                    msg += '\n\n' + errorDetail.description
                                if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')'
                                return alert(msg) // Show a failure message
                            }
                            // Show a success message or redirect
                            alert('Transaction completed!')
                        })
                    }).catch((err) => {
                        alert('Payment could not be captured! ' + JSON.stringify(err))
                    })
                })
            })
        } else {
            // Hides card fields if the merchant isn't eligible
            document.querySelector('#card-form').style = 'display: none'
        }
    </script>
</div>
