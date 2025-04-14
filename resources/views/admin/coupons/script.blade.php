<script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
<script>
    $(function() {


        const TagifyUserListEl = document.querySelector('#TagifyUserList');


        function tagTemplate(tagData) {
            return `
<tag title="${tagData.title || tagData.email}"
contenteditable='false'
spellcheck='false'
tabIndex="-1"
class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ''}"
${this.getAttributes(tagData)}
>
<x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
<div>
  <div class='tagify__tag__avatar-wrap'>
    <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
  </div>
  <span class='tagify__tag-text'>${tagData.name}</span>
</div>
</tag>
`;
        }

        function suggestionItemTemplate(tagData) {
            return `
<div ${this.getAttributes(tagData)}
class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
tabindex="0"
role="option"
>
${
  tagData.avatar
    ? `<div class='tagify__dropdown__item__avatar-wrap'>
            <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
          </div>`
    : ''
}
<div class="fw-medium">${tagData.name}</div>
<span>${tagData.email}</span>
</div>
`;
        }

        function dropdownHeaderTemplate(suggestions) {
            return `
  <div class="${this.settings.classNames.dropdownItem} ${this.settings.classNames.dropdownItem}__addAll">
      <strong>${this.value.length ? `Add remaning` : 'Add All'}</strong>
      <span>${suggestions.length} members</span>
  </div>
`;
        }





        var usersList = [];
        $.ajax({
            url: "{{ route('admin.coupons.get_doctors') }}",
            success: function(res) {
                usersList = res.data

                let TagifyUserList = new Tagify(TagifyUserListEl, {
                    tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
                    enforceWhitelist: true,
                    skipInvalid: true, // do not remporarily add invalid tags
                    dropdown: {
                        closeOnSelect: false,
                        enabled: 0,
                        classname: 'users-list',
                        searchKeys: ['name',
                            'email'
                        ] // very important to set by which keys to search for suggesttions when typing
                    },
                    templates: {
                        tag: tagTemplate,
                        dropdownItem: suggestionItemTemplate,
                        dropdownHeader: dropdownHeaderTemplate
                    },
                    whitelist: usersList
                });

                TagifyUserList.on('dropdown:select',
                        onSelectSuggestion) // allows selecting all the suggested (whitelist) items
                    .on('edit:start',
                        onEditStart); // show custom text in the tag while in edit-mode

                function onSelectSuggestion(e) {
                    // custom class from "dropdownHeaderTemplate"
                    if (e.detail.elm.classList.contains(
                            `${TagifyUserList.settings.classNames.dropdownItem}__addAll`))
                        TagifyUserList.dropdown.selectAll();
                }

                function onEditStart({
                    detail: {
                        tag,
                        data
                    }
                }) {
                    TagifyUserList.setTagTextNode(tag, `${data.name} <${data.email}>`);
                }



                console.log(res)
            }
        })





        // Users List suggestion
        //------------------------------------------------------

    })
</script>
