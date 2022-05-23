document.addEventListener('DOMContentLoaded', function() {
    /*------------------- URL for AJAX requests -------------------*/
    const ajaxRoot = document.getElementsByName('ajaxRoot')[0].content;
    /*------------------- URL for AJAX requests -------------------*/
    /*--------------------- Submitting a form ---------------------*/
    document.querySelector('.scommerce-ajax').addEventListener('submit', function (e) {
        let form = this;
        let method = form.getAttribute('method');
        let submit = form.querySelector('[type="submit"]');

        if (method === null) {
            method = 'post';
        }

        submit.disabled = true;

        fetch(ajaxRoot, {
            method: method,
            cache: "no-store",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: new FormData(form)
        }).then((response) => {
            return response.json();
        }).then((data) => {
            submit.disabled = false;
            console.info('Form', form.querySelector('[name="ajax"]').value+'.', data.message);
        }).catch(function(error) {
            if (error == 'SyntaxError: Unexpected token < in JSON at position 0') {
                console.error('Request failed SyntaxError: The response must contain a JSON string.');
            } else {
                console.error('Request failed', error, '.');
            }
            submit.disabled = false;
        });
        e.preventDefault();
    });
    /*--------------------- Submitting a form ---------------------*/
    /*------------------------ Add to cart ------------------------*/
    const buy = document.querySelectorAll('[data-buy]');
    for (let i = 0; i < buy.length; i++) {
        buy[i].addEventListener("click", function(e) {
            e.preventDefault();

            this.disabled = true;

            let count = 1;
            let option = 0;
            let product = this.getAttribute('data-buy');
            if (this.hasAttribute('data-option')) {
                option = this.getAttribute('data-option');
            }

            let form = new FormData();
            form.append('name', 'buy');
            form.append('product', product);
            form.append('option', option);
            form.append('count', count);

            fetch(ajaxRoot, {
                method: "post",
                cache: "no-store",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: form
            }).then((response) => {
                return response.json();
            }).then((data) => {
                this.disabled = false;
            }).catch(function(error) {
                if (error == 'SyntaxError: Unexpected token < in JSON at position 0') {
                    console.error('Request failed SyntaxError: The response must contain a JSON string.');
                } else {
                    console.error('Request failed', error, '.');
                }
                this.disabled = false;
            });
        });
    }
    /*------------------------ Add to cart ------------------------*/
});