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
            document.getElementById('modal-thank-message').textContent = data.message;
            $.fancybox.open({src:"#thank",type:"inline"});
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
    /*--------------------- Select variation ----------------------*/
    /*const selectVariation = document.querySelectorAll('[data-select-variation]');
    for (let i = 0; i < selectVariation.length; i++) {
        selectVariation[i].addEventListener("change", function(e) {
            console.info('---------test---------')
            console.dir(e);
        })
    }*/
    /*--------------------- Select variation ----------------------*/
    /*------------------------ Add to cart ------------------------*/
    const buy = document.querySelectorAll('[data-buy]');
    for (let i = 0; i < buy.length; i++) {
        buy[i].addEventListener("click", function(e) {
            e.preventDefault();

            this.disabled = true;

            let count = 1;
            let variation = 0;
            let product = this.getAttribute('data-buy');
            if (this.hasAttribute('data-variation')) {
                variation = this.getAttribute('data-variation');
            }

            let form = new FormData();
            form.append('ajax', 'buy');
            form.append('product', product);
            form.append('variation', variation);
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
                document.getElementById('modal-thank-message').textContent = data.message;
                $.fancybox.open({src:"#thank",type:"inline"});
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
$(document).ready(function () {
    /*--------------------- Change variation ----------------------*/
    $(document).on('change', '[data-select-variation]', function () {
        $this = $(this);
        $this.parents('.card').find('[data-variation]').attr('data-variation', $this.find('[selected]').val());
        $this.parents('.card').find('.price__count').text($this.find('[selected]').attr('data-price'));
    });
    /*--------------------- Change variation ----------------------*/
    /*------------------- Filter initialization -------------------*/
    $(document).on("click", "[data-filter]", function () {
        generateFilterUrl($(this).attr("data-filter"), $(this).attr("type"));
        return false;
    });
    $(document).on("click", "[data-price]", function () {
        var priceMin = parseFloat($("[name*='min']").val());
        var priceMax = parseFloat($("[name*='max']").val());
        generateFilterUrl('price='+priceMin+'-'+priceMax);
        return false;
    });

    /*------------------- Filter initialization -------------------*/
});
/*------------------- Filter URL Generator --------------------*/
function generateFilterUrl(filter, type) {
    if (type === undefined) {
        type = 'checkbox';
    }
    var newFilters = [];
    var searchFilter = true;
    // Current url
    var _url   = window.location.pathname;
    var _get   = window.location.search;
    var regex  = /\/f\/(.*)/i;
    // Current filters
    _filterUrl = _url.match(regex);
    if (_filterUrl) {
        // Passed Filter
        var filter = filter.split("=");
        _filterArr = _filterUrl[0].split('/');
        // List of current filters
        _filters   = _filterArr[2].split(';');
        for (var i = 0; i < _filters.length; i++) {
            // If the current filter matches the one passed, add or replace the value
            var _filter = _filters[i].split("=");
            if (filter[0] == _filter[0]) {
                searchFilter = false;
                _values = _filter[1].split(',');
                // Check if a value exists in an array
                _index = _values.indexOf(filter[1]);
                if (_index != -1) {
                    _values.splice(_index, 1);
                } else {
                    _values.push(filter[1]);
                }
                // If the type is radio, delete the value anyway
                if (type == 'radio') {
                    _values = [];
                    _values.push(filter[1]);
                }
                // If the price, still delete the value
                if (filter[0] == 'price') {
                    _values = [];
                    _values.push(filter[1]);
                }
                // If there is something left in the array
                if (_values.length > 0) {
                    _values.sort();
                    _filter[1]  = $.unique(_values).join(',');
                    _filters[i] = _filter.join('=');
                    newFilters.push(_filters[i]);
                }
            } else {
                newFilters.push(_filters[i]);
            }
        }
        // If the current filter does not match any of the existing ones
        if (searchFilter) {
            newFilters.push(filter.join('='));
        }
        // If there are any filters
        if (newFilters.length > 0) {
            newFilters.sort();
            var _newUrl = _url.slice(0, -_filterUrl[0].length)+'/f/'+newFilters.join(';')+'/';
        } else {
            var _newUrl = _url.slice(0, -_filterUrl[0].length)+'/';
        }
    } else {
        var _newUrl = _url+'f/'+filter+'/';
    }

    if (_get.length > 0) {
        window.location.href = _newUrl+_get;
    } else {
        window.location.href = _newUrl;
    }
}
/*------------------- Filter URL Generator --------------------*/