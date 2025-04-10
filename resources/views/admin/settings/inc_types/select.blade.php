<div class="col-md-6">
  <label class="form-label" for="{{ $item->key }}">{{ __("messages.".$item->name ) }}</label>
  <select id="{{ $item->key }}" class="form-control @error($item->key) is-invalid @enderror" name="{{ $item->key }}">
    @php
      use Carbon\Carbon;
      $now = Carbon::now();
      $formats = [
          'Y-m-d' => $now->format('Y-m-d'),        // 2025-03-11
          'd-m-Y' => $now->format('d-m-Y'),        // 11-03-2025
          'm/d/Y' => $now->format('m/d/Y'),        // 03/11/2025
          'd M, Y' => $now->format('d M, Y'),      // 11 Mar, 2025
          'F j, Y' => $now->format('F j, Y'),      // March 11, 2025
          'd-m-Y H:i a' => $now->format('Y-m-d H:i a'), // 2025-03-11 14:30:00
          'H:i' => $now->format('H:i'),            // 14:30
          'l, d F Y' => $now->format('l, d F Y'),  // Tuesday, 11 March 2025
          'D, d M Y H:i:s' => $now->format('D, d M Y H:i:s'), // Tue, 11 Mar 2025 14:30:00
          'M j, Y g:i A' => $now->format('M j, Y g:i A'), // Mar 11, 2025 2:30 PM
          'Y/m/d H:i' => $now->format('Y/m/d H:i'), // 2025/03/11 14:30
          'd-m-Y H:i:s' => $now->format('d-m-Y H:i:s'), // 11-03-2025 14:30:00
          'M d, Y' => $now->format('M d, Y'),  // Mar 11, 2025
          'M d, Y H:i a' => $now->format('M d, Y H:i a'),  // Mar 11, 2025
          'Y M d' => $now->format('Y M d'),  // 2025 Mar 11
      ];
    @endphp
    @foreach($formats as $format => $label)
      <option value="{{ $format }}" {{ old($item->key, $item->value) == $format ? 'selected' : '' }}>
        {{ $label }}
      </option>
    @endforeach
  </select>
  @error($item->key)
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>
