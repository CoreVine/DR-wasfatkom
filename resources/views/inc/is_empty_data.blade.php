
@if (!count($var_check_empty))
<tr>
    <td colspan="{{ $var_check_empty_rows }}">
        <div class="text-center">
            <img width="400" height="400" src="{{ asset('assets/img/empty_data.svg') }}">
        </div>
        <div class="text-center h2">
            لايوجد بيانات :(
        </div>

    </td>
</tr>
@endif
