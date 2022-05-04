document.addEventListener('DOMContentLoaded', function() {
    /*------------------- URL for AJAX requests -------------------*/
    const ajaxRoot = document.getElementsByName('ajaxRoot')[0].content;
    /*------------------- URL for AJAX requests -------------------*/
    /*--------------------- Submitting a form ---------------------*/
    document.querySelector('.scommerce-ajax').addEventListener('submit', function (event) {
        let form = this;
        let method = form.getAttribute('method');
        let submit = form.querySelector('[type="submit"]');

        if (method === null) {
            method = 'post';
        }

        submit.disabled = true;

        fetch(ajaxRoot, {
            method: method,
            body: new FormData(form)
        }).then((response) => {
            return response.json();
        }).then((data) => {
            console.log(data);
            submit.disabled = false;
        }).catch(function(error) {
            if (error == 'SyntaxError: Unexpected token < in JSON at position 0') {
                console.log('Request failed SyntaxError: The response must contain a JSON string.');
            } else {
                console.log('Request failed', error, '.');
            }
            submit.disabled = false;
        });
        event.preventDefault();
    });
    /*--------------------- Submitting a form ---------------------*/
});