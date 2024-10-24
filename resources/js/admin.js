import './bootstrap';
import Choices from "choices.js";
import Swal from "sweetalert2-neutral";
import Sortable from "sortablejs";
import {Datepicker, DateRangePicker} from "vanillajs-datepicker";
import ru from "vanillajs-datepicker/locales/ru";
import Chart from "chart.js/auto";

Object.assign(Datepicker.locales, ru);

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    tooltipList          = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl)),
    forms                = document.querySelectorAll('form'),
    choices              = document.querySelectorAll('.js-choices'),
    mainCategorySelect   = document.querySelector('#productForm [name=category_id]'),
    attributesBlock      = document.getElementById('newAttributes'),
    itemsTable           = document.getElementById('itemsTable'),
    usersTable           = document.getElementById('userTable'),
    orderTable           = document.getElementById('orderTable'),
    createMenuButton     = document.getElementById('jsCreateMenu'),
    editMenuButton       = document.querySelectorAll('.edit-menu'),
    addNavItems          = document.querySelectorAll('.add-items'),
    addGroupItems        = document.getElementById('addFilterGroup'),
    sideBarMenu          = document.querySelectorAll('.sidebar .nav-link'),
    jsConfirm            = document.querySelectorAll('.js-confirm'),
    datepickers          = document.querySelectorAll('.datepicker-item'),
    datepickersRange     = document.querySelectorAll('.datepicker-item-range');

(() => {
    'use strict'
    feather.replace({ 'aria-hidden': 'true' });
    const ctx = document.getElementById('myChart');
    if (ctx) {
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                datasets: [{
                    label: 'Hello Statistics',
                    data: [15339,21345,18483,24003,23489,24092,12034],
                    lineTension: 0,
                    backgroundColor: 'transparent',
                    borderColor: '#786b61',
                    borderWidth: 4,
                    pointBackgroundColor: '#786b61'
                    }]
            },
            options: {
                scales: {
                    y: {ticks:{beginAtZero: false}},
                },
                legend: {
                    display: false
                }
            }
        });
    }
})();


if (mainCategorySelect) {
    mainCategorySelect.addEventListener('change', function (event) {
        const catId = event.target.value,
            formData = new FormData;

        formData.append('id', catId);

        axios.post('/admin/shop/products/get-attributes-form', formData).then(function (response) {
            if (response.status === 200) {
                if (attributesBlock) {
                    attributesBlock.innerHTML     = response.data;
                    attributesBlock.style.display = 'block';
                }
                const choices = attributesBlock.querySelectorAll('.js-choices');
                if(choices.length > 0) {
                    initChoices(choices);
                }
            }
        }).catch(function (error) {
            console.error(error);
        });
    });
}

if (createMenuButton) {
    createMenuButton.addEventListener('click', function (event) {
        event.preventDefault();
        const myModal = document.getElementById('mainModal'),
            modal = new Modal(myModal);

        myModal.addEventListener('hidden.bs.modal', event => {
            myModal.querySelector('.modal-body').innerHTML = '';
        });

        axios.post('/admin/ajax/get-form-menu').then(function (response) {
            if (response.status === 200) {
                myModal.querySelector('.modal-body').innerHTML = response.data;
                modal.show();
            }
        }).catch(function (error) {
            console.error(error)
        })
    })
}

if (editMenuButton.length > 0) {
    editMenuButton.forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            const myModal = document.getElementById('mainModal'),
                modal     = new Modal(myModal),
                formData  = new FormData();

            myModal.addEventListener('hidden.bs.modal', event => {
                myModal.querySelector('.modal-body').innerHTML = '';
            });

            formData.append('menu_id', this.dataset.menuId);

            axios.post('/admin/ajax/get-form-menu', formData).then(function (response) {
                if (response.status === 200) {
                    myModal.querySelector('.modal-body').innerHTML = response.data;
                    modal.show();
                }

            }).catch(function (error) {
                console.error(error)
            })
        });
    });
}

if (itemsTable) {
    const searchForm = itemsTable.querySelector('#searchItems'),
        selectAll    = itemsTable.querySelector('[name=select-all]'),
        allCheckboxes = itemsTable.querySelectorAll('[name="selected[]"]');

    selectAll.addEventListener('change', function (event) {
        allCheckboxes.forEach(function (checkbox) {
            checkbox.checked = event.target.checked;
        });
    });

    Array.from(searchForm.elements).forEach(function (element) {
        if (element.type === 'select-one' || element.type === 'checkbox') {
            element.addEventListener('change', function () {
                searchForm.submit();
            });
        }
        if (element.type === 'text') {
            element.addEventListener('keyup', function (event) {
                if (event.code === 'Enter' || event.code === 'NumpadEnter') {
                    searchForm.submit();
                }
            })
        }
    });
}

if (datepickers.length > 0) {
    datepickers.forEach(function (datepickerEl) {
        const datepickerInput = datepickerEl.querySelector('.datepicker-native'),
            datepicker        = new Datepicker(datepickerInput, {
                language: 'ru',
                autohide: true,
                clearButton: true,
            });
    });
}

if(datepickersRange.length > 0) {
    datepickersRange.forEach(function (element) {
        const rangepicker = new DateRangePicker(element, {
            language: 'ru',
            autohide: true,
            clearButton: true,
        });
    });
}

if (addGroupItems) {
    let newGroupCount = 0;
    addGroupItems.addEventListener('click', function (event) {
        axios.post('/admin/shop/filters/add-group').then(function (response) {
            if (response.status === 200) {
                const html = new DOMParser().parseFromString(response.data, 'text/html');
                newGroupCount++;
                html.querySelector('.accordion-header').id = 'heading-new-'+newGroupCount;
                const collapseBlock = html.querySelector('.accordion-collapse'),
                    buttonCollapse  = html.querySelector('.accordion-header button'),
                    inputName       = html.querySelector("[name='group_name[]']"),
                    labelName       = inputName.nextElementSibling,
                    inputCategories = html.querySelector("[name='group_categories[][]']"),
                    inputTags       = html.querySelector("[name='tags[][]']"),
                    inputAttributes = html.querySelector("[name='attributes[][]']"),
                    inputCheckbox   = html.querySelector("[name='display_head[]']"),
                    labelCheckbox   = inputCheckbox.nextElementSibling,
                    container       = document.getElementById('accordionGroups');

                inputName.setAttribute('name', 'group_name[new-'+newGroupCount+']');
                inputName.id = 'groupName-'+newGroupCount;
                labelName.setAttribute('for', 'groupName-'+newGroupCount);

                inputCategories.setAttribute('name', 'group_categories[new-'+newGroupCount+'][]');
                inputAttributes.setAttribute('name', 'attributes[new-'+newGroupCount+'][]');
                inputTags.setAttribute('name', 'tags[new-'+newGroupCount+'][]');

                inputCheckbox.setAttribute('name', 'display_head[new-'+newGroupCount+']');
                inputCheckbox.id = 'groupDisplayHead-'+newGroupCount;
                labelCheckbox.setAttribute('for', 'groupDisplayHead-'+newGroupCount);

                collapseBlock.classList.remove('show');
                collapseBlock.id = 'collapse-new-'+newGroupCount;
                collapseBlock.setAttribute('aria-labelledby', 'heading-new-'+newGroupCount);

                buttonCollapse.dataset.bsTarget = '#collapse-new-'+newGroupCount;
                buttonCollapse.setAttribute('aria-expanded', 'false');
                buttonCollapse.setAttribute('aria-controls', 'collapse-new-'+newGroupCount);

                const choices = html.querySelectorAll('.js-choices');
                if(choices.length > 0) {
                    initChoices(choices);
                }

                container.append(html.querySelector('.accordion-item'));
            }

        }).catch(function (error) {
            console.error(error);
        });
    });
}

function jsConfirmation(jsConfirm, form = null) {
    jsConfirm.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const actionForm = !form ? button.closest('form') : form,
                self         = this;
            Swal.fire({
                title: self.dataset.confirm === 'multi'? 'Вы уверены что хотите удалить эти записи?' : 'Вы уверены что хотите удалить эту запись?',
                icon: 'warning',
                showCancelButton: true,
                showConfirmButton:true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да',
                cancelButtonText: 'Нет',
            }).then(function (data) {
                if(data.isConfirmed) {
                    if (form) {
                        document.body.appendChild(actionForm);
                    }
                    if (typeof button.name !== 'undefined' && typeof button.value !== 'undefined') {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = button.name;
                        input.value = button.value;
                        actionForm.append(input);
                    }
                    actionForm.submit();
                }
            })
        });
    });
}

if (jsConfirm.length > 0) {
    jsConfirmation(jsConfirm);
}

class MenuManage extends HTMLElement
{
    constructor() {
        super();
        this.addButton      = this.querySelector('.js-add-item');
        this.itemsContainer = this.querySelector('.menu-items-container');

        if (this.addButton) {
            this.addItem(this.addButton);
        }
        this.initSortable(this.itemsContainer.querySelectorAll('.list-group'));

        this.initControlButtons();

        document.addEventListener('click', function (event) {
            if (!event.target.classList.contains('answer-item') && !event.target.classList.contains('menu-item-input')) {
                const containers = document.querySelectorAll('.answer-container');
                if (containers.length > 0) {
                    containers.forEach(function (container) {
                        const containerBox = container.closest('.list-group-item'),
                            itemInput      = containerBox.querySelector('input');
                        if (itemInput) {
                            itemInput.value = '';
                        }
                        container.remove();
                    })
                }
            }
            if (!event.target.classList.contains('edit-item-title') && !event.target.classList.contains('form-control')) {
                const newInputs = document.querySelectorAll('.new-title.show');
                if (newInputs.length > 0) {
                    newInputs.forEach(function (newInput) {
                        newInput.classList.remove('show');
                    });
                }
            }
        });
    }

    initElements() {
        this.addButton = this.querySelector('.js-add-item');
        if (this.addButton) {
            this.addItem(this.addButton);
        }
        this.initSortable(this.itemsContainer.querySelectorAll('.list-group'));
        this.initControlButtons();
    }

    setSortAttributes(items, parent = null) {
        const self = this;
        Array.from(items).forEach(function (element, index) {
            const currentId = element.dataset.id,
                children    = element.querySelector('.list-group'),
                sortName    = 'input[name="items[' + currentId + '][sort]"]',
                parentName  = 'input[name="items[' + currentId + '][parent]"]',
                inputSort   = element.querySelector(sortName);

            element.dataset.sort = index.toString();
            inputSort.value      = index.toString();

            const inputParent = element.querySelector(parentName);

            if (parent) {
                inputParent.value          = parent.toString();
                element.dataset.itemParent = parent;
            } else {
                inputParent.value          = '0';
                element.dataset.itemParent = '0';
            }

            if (children.children.length > 0) {
                self.setSortAttributes(children.children, currentId);
            }
        });
    }

    selectListener(event) {
        if (!event.target.classList.contains('answer-item')) {
            return false;
        }
        const box = event.target.closest('.list-group-item'),
            inputHiddenType = document.createElement('input'),
            inputHiddenId   = document.createElement('input');

        if (box && typeof box !== 'undefined') {

            if (event.target.dataset.type === 'external' && !this.isValidHttpUrl(event.target.textContent.replace(event.target.querySelector('span').textContent, ''))) {
                const errorBlock = document.createElement("span");

                errorBlock.classList.add('invalid-feedback');
                errorBlock.textContent = 'Неверный URL адрес!';

                box.querySelector('input[type=text]').classList.add('is-invalid');
                box.querySelector('input[type=text]').nextSibling.after(errorBlock);

                return;
            }

            inputHiddenType.type  = 'hidden';
            inputHiddenType.name  = 'items[' + box.dataset.id + '][type]';
            inputHiddenType.value = event.target.dataset.type;

            inputHiddenId.type  = 'hidden';
            inputHiddenId.name  = 'items[' + box.dataset.id + '][item_id]';
            inputHiddenId.value = event.target.dataset.id;

            box.append(inputHiddenType);
            box.append(inputHiddenId);

            box.querySelector('input[type=text]').value                   = event.target.textContent.replace(event.target.querySelector('span').textContent, '');
            box.querySelector('input[type=text]').placeholder             = event.target.querySelector('span').textContent;
            box.querySelector('input[type=text]').nextSibling.textContent = event.target.querySelector('span').textContent;

            event.target.parentElement.removeEventListener('click', this.selectListener);
            event.target.parentElement.remove();
        }

    }

    arrowHandler(event) {
        let answerList = event.target.closest('.list-group-item').querySelectorAll('.answer-item'),
            index      = Array.from(answerList).indexOf(this.querySelector('.answer-item.active'));
        if (event.key === 'ArrowDown') {
            if (typeof answerList[++index] !== 'undefined') {
                answerList[index].classList.add('active');
            }
            if (typeof answerList[--index] !== 'undefined') {
                answerList[index].classList.remove('active');
            }
        }
        if (event.key === 'ArrowUp') {
            if (typeof answerList[--index] !== 'undefined') {
                answerList[index].classList.add('active');
            }
            if (typeof answerList[++index] !== 'undefined') {
                answerList[index].classList.remove('active');
            }
        }
    }

    isValidHttpUrl(string) {
        let url;
        try {
            url = new URL(string);
        } catch (_) {
            return false;
        }
        return url.protocol === 'http:' || url.protocol === 'https:' || url.protocol === 'mailto:';
    }

    enterHandler(event) {
        const container       = event.target.closest('.list-group-item'),
            element           = container.querySelector('.answer-item.active');

        if (!element) {return;}

        const type            = element.dataset.type,
            typeName          = element.querySelector('span').textContent,
            title             = element.textContent.replace(typeName, ''),
            itemId            = element.dataset.id,
            id                = container.dataset.id,
            hiddenInputType   = document.querySelector('input[name="items[' + id + '][type]"]') ??
                document.createElement('input'),
            hiddenInputItemId = document.querySelector('input[name="items[' + id + '][item_id]"]') ??
                document.createElement('input');

        hiddenInputType.type  = 'hidden';
        hiddenInputType.name  = 'items['+id+'][type]';
        hiddenInputType.value = type;

        hiddenInputItemId.type  = 'hidden';
        hiddenInputItemId.name  = 'items['+id+'][item_id]';
        hiddenInputItemId.value = itemId;

        if (type === 'external' && !this.isValidHttpUrl(event.target.value)) {
            const errorBlock     = document.createElement("span");
            errorBlock.classList.add('invalid-feedback');
            errorBlock.textContent = 'Неверный URL адрес!';
            event.target.classList.add('is-invalid');
            event.target.nextSibling.after(errorBlock);
            return;
        }

        event.target.value              = title;
        event.target.placeholder        = typeName;
        event.target.nextSibling.textContent = typeName;

        container.append(hiddenInputType);
        container.append(hiddenInputItemId);

        container.querySelector('.answer-container').remove();
    }

    renderAnswer(answer, event) {
        const targetContainer = event.target.closest('.list-group-item'),
            answerContainer   = targetContainer.querySelector('.answer-container') ?? document.createElement('div'),
            self              = this,
            form              = event.target.closest('form');

        form.addEventListener('keydown', function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                event.stopPropagation();
            }
        });

        answerContainer.classList.add('answer-container');

        answerContainer.innerHTML = '';

        answer.forEach(function (item) {
            const answerItem = document.createElement('div'),
                answerType   = document.createElement('span');

            answerItem.classList.add('answer-item');
            answerItem.dataset.type = item.type;
            answerItem.dataset.id   = item.model_id;
            answerItem.textContent  = item.name ?? item.title;
            answerType.textContent  = item.model;
            answerItem.append(answerType);

            if (!answerContainer.querySelector('#'+self.type+item.id)) {
                answerContainer.append(answerItem);
            }
        });
        if (!targetContainer.querySelector('.answer-container')) {
            targetContainer.append(answerContainer);
            targetContainer.removeEventListener('click', self.selectListener.bind(self),false);
            targetContainer.addEventListener('click', self.selectListener.bind(self), false);
        } else {
            if (event.code === 'ArrowDown' || event.code === 'ArrowUp') {
                this.arrowHandler(event);
            }
            if (event.keyCode === 13) {
                this.enterHandler(event);
            }
        }
    }

    initSortable (sortables) {
        const self = this;
        for (let i = 0; i < sortables.length; i++) {
            new Sortable(sortables[i], {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onSort: self.updateSort.bind(self),
            });
        }
    }

    removeErrors() {
        const errorInputs = this.querySelectorAll('.is-invalid'),
            errorBlocks  = this.querySelectorAll('.invalid-feedback');

        if (errorInputs.length > 0) {
            errorInputs.forEach(function (errorInput) {
                errorInput.classList.remove('is-invalid');
            });
        }
        if (errorBlocks.length > 0) {
            errorBlocks.forEach(function (errorBlock) {
                errorBlock.remove();
            });
        }
    }

    find(event) {
        const formData = new FormData,
            self       = this;
        formData.append('query', event.target.value);

        if (event.target.value === '') {
            this.removeErrors();
        }

        if (event.code !== 'ArrowDown' && event.code !== 'ArrowUp' && event.keyCode !== 13) {
            axios.post('/admin/navigations/find',formData).then(function (response) {
                if (response.status === 200) {
                    const answer = response.data;
                    if (Array.isArray(answer) && answer.length > 0) {
                        self.renderAnswer(answer.slice(0, 10), event);
                        self.removeErrors();
                    } else if (answer === '' || (Array.isArray(answer) && answer.length === 0)) {
                        const targetContainer = event.target.closest('.list-group-item'),
                            answerContainer   = targetContainer.querySelector('.answer-container');
                        if (answerContainer && event.target.value === '') {
                            answerContainer.remove();
                        } else if (event.target.value !== '') {
                            const customItemAnswer = {
                                    'name': event.target.value,
                                    'type': event.target.value === '#' ? 'separator' : 'external',
                                    'model': event.target.value === '#' ? 'Разделитель' : 'Внешний',
                                    'model_id':0
                                },
                                customAnswer = [customItemAnswer];
                            self.renderAnswer(customAnswer, event);
                        }
                    }
                }
            }).catch(function (error) {
                console.error(error);
            });
        } else if (event.keyCode === 13) {
            this.enterHandler(event);
        } else {
            this.arrowHandler(event);
        }
    }

    updateSort() {
        const draggableContainer = document.getElementById('draggable');
        if (draggableContainer.children.length > 0) {
            this.setSortAttributes(draggableContainer.children);
        }
    }

    initControlButtons() {
        const buttons = this.itemsContainer.querySelectorAll('.control-item-buttons button');
        buttons.forEach(function (button) {
            const tooltipBtn = new Tooltip(button);

            if (button.classList.contains('delete-item') || button.classList.contains('remove-item-image')) {
                const form = document.createElement('form'),
                    inputMethod = document.createElement('input'),
                    inputCSRF   = document.createElement('input');

                form.action = button.classList.contains('delete-item') ?
                    '/admin/navigations/menu-item-delete/'+button.dataset.itemId :
                    '/admin/navigations/menu-item-delete-image/'+button.dataset.itemId;
                form.method       = 'POST';
                inputMethod.type  = 'hidden';
                inputMethod.name  = '_method';
                inputMethod.value = 'DELETE';

                inputCSRF.type  = 'hidden';
                inputCSRF.name  = '_token';
                inputCSRF.value = document.querySelector('meta[name="csrf-token"]').content;

                form.append(inputMethod);
                form.append(inputCSRF);

                jsConfirmation([button], form);
            } else {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    tooltipBtn.hide();
                    if (button.classList.contains('edit-item-title')) {
                        const newTitle = event.target.closest('.control-item-buttons').querySelector('.new-title');
                        newTitle.classList.add('show');
                    }
                    if (button.classList.contains('add-item-image')) {
                        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                        inputId = 'navImage'+button.dataset.itemId
                    }
                });
            }
        });
    }

    addItem(button) {
        let self = this;
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const wrapperItem      = document.createElement('div'),
                wrapperDraggable   = document.createElement('div'),
                containerDraggable = document.createElement('div'),
                inputItemTitle     = document.createElement('input'),
                inputHiddenId      = document.createElement('input'),
                inputHiddenSort    = document.createElement('input'),
                inputHiddenParent  = document.createElement('input'),
                labelItemTitle     = document.createElement('label'),
                listDraggable      = self.itemsContainer.querySelector('#draggable');

            let length = self.itemsContainer.querySelectorAll('.menu-item-input').length;

            if (self.itemsContainer.querySelectorAll('.list-group-item').length > 0) {
                const lengthArray = [];
                self.itemsContainer.querySelectorAll('.list-group-item').forEach(function (item) {
                    lengthArray.push(item.dataset.id);
                });
                length = Math.max(...lengthArray);
            }

            inputItemTitle.classList.add('form-control', 'menu-item-input');
            inputItemTitle.id          = 'menuItemInput-' + (length + 1);
            inputItemTitle.name        = 'items[' + (length + 1) + '][title]';
            inputItemTitle.placeholder = '-= Начните набирать текст =-';
            inputItemTitle.type        = 'text';
            inputItemTitle.setAttribute('autocomplete', 'off');

            wrapperItem.classList.add('form-floating');

            labelItemTitle.classList.add('form-label');
            labelItemTitle.textContent = '-= Начните набирать текст =-';
            labelItemTitle.setAttribute('for', 'menuItemInput-'+(length+1));

            containerDraggable.classList.add('list-group', 'nested-sortable');

            wrapperDraggable.classList.add('list-group-item', 'nested-'+(length+1));
            wrapperDraggable.dataset.id   = (length + 1).toString();
            wrapperDraggable.dataset.sort = length.toString();

            inputHiddenParent.type  = 'hidden';
            inputHiddenParent.name  = 'items[' + (length + 1) + '][parent]';
            inputHiddenParent.value = '0';

            inputHiddenSort.type  = 'hidden';
            inputHiddenSort.name  = 'items[' + (length + 1) + '][sort]';
            inputHiddenSort.value = (length + 1).toString();

            inputHiddenId.type  = "hidden";
            inputHiddenId.name  = 'items[' + (length +1) + '][id]';
            inputHiddenId.value = (length + 1).toString();

            wrapperItem.append(inputItemTitle);
            wrapperItem.append(labelItemTitle);
            wrapperItem.append(inputHiddenId);
            wrapperItem.append(inputHiddenSort);
            wrapperItem.append(inputHiddenParent);

            wrapperDraggable.append(wrapperItem);
            wrapperDraggable.append(containerDraggable);
            listDraggable.append(wrapperDraggable);

            inputItemTitle.addEventListener('keyup', self.find.bind(self));

            const nestedSortables = self.itemsContainer.querySelectorAll('.list-group');

            self.initSortable(nestedSortables);
        });
    }
}
customElements.define('menu-manage', MenuManage);

if (addNavItems.length > 0) {
    addNavItems.forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            const formData     = new FormData,
                itemsContainer = document.querySelector('.menu-items-container');

            formData.append('menu_id', this.dataset.menuId);

            axios.post('/admin/ajax/get-form-menu-items', formData).then(function (response) {
                if (response.status === 200) {
                    itemsContainer.innerHTML = response.data;
                    itemsContainer.parentElement.initElements();
                    if (itemsContainer.querySelector('#draggable').childElementCount > 0) {
                        feather.replace({ 'aria-hidden': 'true' });
                    }
                }
            }).catch(function (error) {
                console.error(error);
            });
        });
    });
}

if (sideBarMenu.length > 0) {
    sideBarMenu.forEach(function (element) {
        element.addEventListener('click', function (event) {
            let nextEl = element.nextElementSibling,
                parentEl = element.parentElement;

            if (nextEl) {
                event.preventDefault();
                let myCollapse = new Collapse(nextEl);

                if (nextEl.classList.contains('show')) {
                    myCollapse.hide();
                } else {
                    myCollapse.show();

                    let openedSubmenu = parentEl.parentElement.querySelector('.submenu.show');

                    if (openedSubmenu) {
                        new Collapse(openedSubmenu);
                    }
                }
            }
        });
    });
}

function toggleGallery(elements, forms, mainPhotos) {
    if (elements.length === 0) {
        console.error('Не найдены изображения');
        return null;
    }
    elements.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            const photoId = this.closest('.thumb-item').dataset.photoId;
            elements.forEach(function (element) {
                element.closest('.thumb-item').classList.remove('active');
            });
            forms.forEach(function (form) {
                if (typeof form.dataset.photoId !== 'undefined' && form.dataset.photoId === photoId) {
                    form.classList.add('active');
                } else {
                    form.classList.remove('active');
                }
            });
            mainPhotos.forEach(function (mainPhoto) {
                if (typeof mainPhoto.dataset.photoId !== 'undefined' && mainPhoto.dataset.photoId === photoId) {
                    mainPhoto.classList.add('active');
                } else {
                    mainPhoto.classList.remove('active');
                }
            });
            this.closest('.thumb-item').classList.add('active');
        });
    });
}

function submitForms(forms, refresh = false) {
    if(forms.length === 0) {
        console.error('Формы не найдены');
        return null;
    }
    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(form);
            axios.post(form.action, formData).then(function (response) {
                const answer = response.data,
                    answerBlock = document.createElement('div');
                answerBlock.classList.add('alert');
                if (typeof answer.error !== 'undefined') {
                    answerBlock.classList.add('bg-danger');
                    answerBlock.innerText = answer.error;
                } else if (typeof answer.success !== 'undefined') {
                    answerBlock.classList.add('bg-success');
                    answerBlock.innerText = answer.success;
                }
                answerBlock.classList.add('text-white');
                if (refresh) {
                    window.location.reload();
                }
                form.prepend(answerBlock);
            }).catch(function (error) {
                console.error(error);
            })
        });
    });
}

function initChoices(elements) {
    elements.forEach(function (element) {
        let config = {
            loadingText: 'Загрузка...',
            noResultsText: 'Не найдено',
            noChoicesText: 'Нет выбора',
            itemSelectText: 'Выбрать',
            uniqueItemText: 'Можно добавлять только уникальные значения',
            customAddItemText: 'Можно добавлять только значения, соответствующие определенным условиям',
            placeholder:true,
            placeholderValue: element.dataset.placeholder,
            shouldSort: false,
            removeItems: true,
            removeItemButton: true,
            duplicateItemsAllowed: false,
            allowHTML: true,
            fuseOptions: {
                includeScore: true,
                threshold:0.5
            },
        }
        if (typeof element.dataset.customTemplate !== 'undefined') {
            config.callbackOnCreateTemplates = function (template) {
                return {
                    item: ({ classNames }, data) => {
                        if (data.value.indexOf('|') !== -1) {
                            const arrayValue = data.value.split('|'),
                                color = arrayValue[1];
                            if (typeof color !== 'undefined') {
                                return template(`
                                            <div class="${classNames.item} ${
                                    data.highlighted
                                        ? classNames.highlightedState
                                        : classNames.itemSelectable
                                } ${
                                    data.placeholder ? classNames.placeholder : ''
                                }" data-item data-id="${data.id}" data-value="${data.value}" ${
                                    data.active ? 'aria-selected="true"' : ''
                                } ${data.disabled ? 'aria-disabled="true"' : ''}>
                                    <i style="display: inline-block;width: 16px;height: 16px;background: #${color};border: 1px solid #000"></i>${data.label.split('|')[0]}<button type="button" class="choices__button" data-button="">Remove item</button></div>
                                `);
                            }
                        } else {
                            return template(`
                                            <div class="${classNames.item} ${
                                data.highlighted
                                    ? classNames.highlightedState
                                    : classNames.itemSelectable
                            } ${
                                data.placeholder ? classNames.placeholder : ''
                            }" data-item data-id="${data.id}" data-value="${data.value}" ${
                                data.active ? 'aria-selected="true"' : ''
                            } ${data.disabled ? 'aria-disabled="true"' : ''}>${data.label}</div>`);
                        }
                    },
                    choice: ({ classNames }, data) => {
                        if (data.value.indexOf('|') !== -1) {
                            const arrayValue = data.value.split('|'),
                                color = arrayValue[1];
                            if (typeof color !== 'undefined') {
                                return template(`
                                            <div class="${classNames.item} ${classNames.itemChoice} ${
                                    data.disabled ? classNames.itemDisabled : classNames.itemSelectable
                                }" data-select-text="${this.config.itemSelectText}" data-choice ${
                                    data.disabled
                                        ? 'data-choice-disabled aria-disabled="true"'
                                        : 'data-choice-selectable'
                                } data-id="${data.id}" data-value="${data.value}" ${
                                    data.groupId > 0 ? 'role="treeitem"' : 'role="option"'
                                }><i style="display: inline-block;width: 16px;height: 16px;background: #${color};border: 1px solid #000"></i>${data.label.split('|')[0]}</div>`);
                            }
                        } else {
                            return template(`
                                            <div class="${classNames.item} ${classNames.itemChoice} ${
                                data.disabled ? classNames.itemDisabled : classNames.itemSelectable
                            }" data-select-text="${this.config.itemSelectText}" data-choice ${
                                data.disabled
                                    ? 'data-choice-disabled aria-disabled="true"'
                                    : 'data-choice-selectable'
                            } data-id="${data.id}" data-value="${data.value}" ${
                                data.groupId > 0 ? 'role="treeitem"' : 'role="option"'
                            }>${data.label}</div>`);
                        }
                    },
                }
            }
        }
        new Choices(element, config);

        element.addEventListener('removeItem', function (event) {
            if ('action' in event.target.dataset) {
                const url    = event.target.dataset['action'],
                    id       = event.detail.value,
                    formData = new FormData(),
                    overlay  = document.getElementById('mainOverlay');

                overlay.classList.add('show');
                document.body.classList.add('loading');

                formData.append('id', id);
                axios.post(url, formData).then(function (response) {
                    const tempBlock = document.createElement('div');
                    tempBlock.innerHTML = response.data;
                    const alert = tempBlock.querySelector('.alert'),
                        block   = document.querySelector('main');

                    if (document.querySelectorAll('.alert').length > 0) {
                        document.querySelectorAll('.alert').forEach(function (element) {
                            element.remove();
                        });
                    }

                    block.prepend(alert);
                    overlay.classList.remove('show');
                    document.body.classList.remove('loading');
                }).catch(function (error) {
                    console.error(error);
                })
            }
        });
    });
}

if (choices.length > 0) {
    initChoices(choices);
}

class InputTags extends HTMLElement
{
    constructor() {
        super();
        const InputSelect = this.querySelector('select'),
            existsChoices = [];

        InputSelect.querySelectorAll('option').forEach(function (element) {
            if (element.getAttribute("value") !== '') {
                existsChoices.push({"value": element.getAttribute("value"), "label": element.textContent});
            }
        });

        this.init(InputSelect);
        this.getExistsChoices = function () {return existsChoices};
    }
    init(InputSelect) {
        initChoices([InputSelect]);
        this.SearchInput = this.querySelector('input[type="search"]');
        this.SearchInput.addEventListener('keyup', this.searchTags.bind(this));
    }

    checkKey(key, code) {
        return ((key >= '0' && key <= '9') || (/[0-9a-zа-яё]+/i.test(key)) && /^Key*/i.test(code));
    }

    searchTags(event) {
        const choices = this.choices,
            searchInput = this.SearchInput;

        if (event.code === 'Enter' && this.SearchInput.value !== '') {
            const formData = new FormData(),
                meta       = {
                    "title"      : "Товары по тегу "+event.target.value,
                    "description": "На данной странице представлены товары с тегом "+event.target.value
                },
                existsChoices   = this.getExistsChoices(),
                selectedChoices = choices.getValue();

            if(
                selectedChoices.find(el => el.label === event.target.value) ||
                existsChoices.find(el => el.label === event.target.value)
            ) {
                console.warn('Дубликат');
                return null;
            }

            let lostChoices = existsChoices.map(
                function (el) {
                    if(!selectedChoices.find(selected => Number(selected.value) === Number(el.value))) {
                        return el;
                    }
                });

            formData.append('name', event.target.value);
            formData.append('meta.title', meta.title);
            formData.append('meta.description', meta.description);

            axios.post('/admin/shop/tags/create-ajax', formData).then(function (response) {
                if (response.status === 200) {
                    const answer = response.data;
                    if (answer.success) {
                        lostChoices.push({value: answer.id, label:event.target.value});
                        choices.setChoices(lostChoices, 'value', 'label', true);
                        choices.setChoiceByValue(answer.id);
                        searchInput.value = '';
                    } else {
                        console.error(answer.error);
                    }
                }
            }).catch(function (error) {
                console.error(error);
            });

        }
    }
}
customElements.define('input-tags', InputTags);
class Images extends HTMLElement
{
    constructor() {
        super();
        const input       = this.querySelector('input[type=file]');
        const container   = this.querySelector('.images-container');
        const existsImage = this.querySelectorAll('.wrapper-image');

        this.modalElement = document.getElementById('mainModal');
        this.modal        = new Modal(this.modalElement);

        this.getModalElement = function () {return this.modalElement}
        this.getContainer    = function () {return container}

        if (input) {
            input.addEventListener('change', this.changeImage.bind(this));
        }

        this.modalElement.addEventListener('hide.bs.modal', function () {
            this.querySelector('.modal-dialog').classList.remove('modal-fullscreen');
            this.querySelector('.modal-body').innerHTML = '';
            this.classList.remove('dark');
        });

        if (existsImage.length > 0) {
            this.editOptions(existsImage);
        }
    }
    editOptions(exists) {
        const self = this;

        exists.forEach(function (element) {
            element.addEventListener('click', function (event) {
                event.preventDefault();
                const formData  = new FormData(),
                    photoId     = this.dataset.photoId,
                    photoOwner  = this.dataset.photoOwner,
                    productId   = this.dataset.productId,
                    categoryId  = this.dataset.categoryId,
                    postId      = this.dataset.postId,
                    promotionId = this.dataset.promotionId;
                formData.append('owner', photoOwner);
                formData.append('id', photoId);
                if (typeof productId !== 'undefined')
                    formData.append('product_id', productId);
                if (typeof categoryId !== 'undefined')
                    formData.append('category_id', categoryId);
                if (typeof promotionId !== 'undefined')
                    formData.append('promotion_id', promotionId);
                if (typeof postId !== 'undefined')
                    formData.append('post_id', postId);

                axios.post('/admin/photos/get-photos', formData).then(function (response) {
                    if (response.status === 200) {
                        const answer = response.data;
                        if (answer.error) {
                            console.log(response);
                            console.error(answer.error);
                            return null;
                        } else {
                            self.getModalElement().classList.add('dark');
                            self.getModalElement().querySelector('.modal-dialog').classList.add('modal-fullscreen');
                            self.getModalElement().querySelector('.modal-body').innerHTML = answer;
                            self.modal.show();
                            const thumbs   = self.getModalElement().querySelectorAll('.thumb-photo'),
                                forms      = self.getModalElement().querySelectorAll('form'),
                                mainPhotos = self.getModalElement().querySelectorAll('.main-photo');
                            toggleGallery(thumbs, forms, mainPhotos);
                            submitForms(forms);

                            const closeButtons = self.getModalElement().querySelectorAll('[data-bs-dismiss="modal"]');
                            if (closeButtons.length > 0) {
                                closeButtons.forEach(function (button) {
                                    button.addEventListener('click', function (event) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                        self.modal.hide();
                                    });
                                });
                            }
                        }
                    }
                }).catch(function (error) {
                    console.error(error);
                });
            })
        })
    }

    changeImage(event) {

        const files = event.currentTarget.files,
            container = this.getContainer(),
            newItems  = container.querySelectorAll('.image-item.new');

        newItems.forEach(function (item) {
            item.remove();
        });

        Array.from(files).forEach(function (file, index) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const image = new Image(),
                    wrapperImage = document.createElement('div'),
                    imageItem    = document.createElement('div');

                image.src = e.target.result.toString();
                imageItem.classList.add('image-item', 'new');
                imageItem.setAttribute('style', 'max-height:142px');
                wrapperImage.classList.add('wrapper-image');
                wrapperImage.append(image);
                imageItem.append(wrapperImage);
                container.append(imageItem);
            };
            reader.readAsDataURL(files[index]);
        });
    }
}

customElements.define('up-images', Images);

class VariantsList extends HTMLElement
{
    constructor() {
        super();
        this.variants        = this.querySelectorAll('.variant');
        this.addVariantBtn   = this.querySelector('.js-addVariant');
        this.deleteButtons   = this.querySelectorAll('[js-deleteVariant]');
        this.type            = this.dataset.type;

        this.modalElement    = document.getElementById('mainModal');
        this.modal           = new Modal(this.modalElement);
        this.getModal        = function () {return this.modal}
        this.getModalElement = function () {return this.modalElement}
        this.getType         = function () {return this.type}

        this.modalElement.addEventListener('hide.bs.modal', function () {
            this.querySelector('.modal-dialog').classList.remove('modal-fullscreen');
            this.querySelector('.modal-body').innerHTML = '';
            this.querySelector('.modal-title').textContent = '';
            this.classList.remove('dark');
        });

        this.addVariantBtn.addEventListener('click', this.addVariant.bind(this));
        this.deleteVariants(this.deleteButtons);

        // this.init(this.variants);
    }

    deleteVariants(buttons) {
        if (buttons.length > 0) {
            buttons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const variantId = event.target.getAttribute('js-deleteVariant'),
                        formData    = new FormData,
                        url         = '/admin/shop/products/delete-relation';

                    formData.append('variant_id', variantId);
                    formData.append('current_product', event.target.dataset.currentProduct);

                    console.log(formData.get('variant_id'), formData.get('current_product'));

                    axios.post(url, formData).then(function (response) {
                        console.log();
                        if (response.data === 'success') {
                            window.location.reload();
                        }
                    }).catch(function (error) {
                        console.error(error);
                    });
                });
            });
        }
    }

    selectVariant(event) {
        event.preventDefault();
        const form      = event.target.closest('form'),
            inputHidden = document.createElement('input'),
            tokenMeta   = document.querySelector('meta[name="csrf-token"]');

        inputHidden.type  = 'hidden';
        inputHidden.name  =  '_token';
        inputHidden.value = tokenMeta.content;

        form.prepend(inputHidden);

        form.querySelector('input#variantId').value = event.target.dataset.productId;
        event.target.closest('.result-items').remove();
    }

    searchVariant(event) {
        event.preventDefault();
        if (event.target.value.length > 2) {
            const query  = event.target.value,
                formData = new FormData,
                self     = this;

            formData.append('query', query);

            axios.post('/admin/shop/products/search-relation', formData).then(function (response) {

                const answer    = response.data,
                    container   = document.createElement('div'),
                    destination = document.querySelector('.modal-body form .result-items') ?? document.querySelector('.modal-body form > div');

                container.classList.add('result-items','mb-3');

                if (Array.isArray(answer) && answer.length > 0) {

                    answer.map(function (element) {
                        const item = document.createElement('div');
                        item.classList.add('result-item');
                        item.textContent       = element.name;
                        item.dataset.productId = element.id;
                        container.append(item);
                        item.addEventListener('click', self.selectVariant.bind(self));
                    });

                    if (document.querySelector('.modal-body form .result-items')) {
                        destination.innerHTML = '';
                        Array.from(container.querySelectorAll('.result-item')).map(function (itm) {
                            destination.append(itm);
                        });
                    }  else {
                        destination.after(container);
                    }

                } else if (Array.isArray(answer) && answer.length === 0) {
                    container.innerHTML = '<p class="text-center my-4">По запросу ничего не найдено!</p>';
                    if (document.querySelector('.modal-body form .result-items')) {
                        destination.innerHTML = container.innerHTML;
                    }  else {
                        destination.after(container);
                    }
                }

            }).catch(function (error) {
                console.error(error);
            })
        } else if (document.querySelector('.modal-body form .result-items')) {
            document.querySelector('.modal-body form .result-items').remove();
        }
    }

    addVariant(event) {
        event.preventDefault();
        const form      = document.createElement('form'),
            inputText   = document.createElement('input'),
            inputHidden = document.createElement('input'),
            label       = document.createElement('label'),
            wrapper     = document.createElement('div'),
            submitBtn   = document.createElement('button');

        form.action = '/admin/shop/products/add-relation';
        form.method = 'post';

        inputHidden.name  = 'current_product';
        inputHidden.type  = 'hidden';
        inputHidden.value = this.dataset.productId

        inputText.name        = 'variant_id';
        inputText.type        = 'text';
        inputText.id          = 'variantId';
        inputText.placeholder = 'Поиск продукта';
        inputText.classList.add('form-control');

        label.setAttribute('for', 'variantId');
        label.textContent = 'Поиск продукта';

        submitBtn.type = 'submit';
        submitBtn.textContent = 'Сохранить';
        submitBtn.classList.add('btn', 'btn-success', 'w-100')

        wrapper.classList.add('form-floating','mb-3');

        wrapper.append(inputText);
        wrapper.append(label);

        form.append(inputHidden);
        form.append(wrapper);
        form.append(submitBtn);

        this.getModalElement().querySelector('.modal-body').append(form);
        this.getModalElement().querySelector('.modal-title').textContent = 'Поиск варианта (начните набирать текст)';
        this.getModal().show();

        inputText.addEventListener('keyup', this.searchVariant.bind(this));
    }
}
customElements.define('variant-list', VariantsList);
