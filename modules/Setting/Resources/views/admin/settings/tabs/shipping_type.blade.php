{{ Form::number('super_light_rate', 'Super Light Rate', $errors, $settings, ['min' => 0, 'step' => 0.01]) }}
{{ Form::number('light_rate', 'Light Rate', $errors, $settings, ['min' => 0, 'step' => 0.01]) }}
{{ Form::number('medium_rate', 'Medium Rate', $errors, $settings, ['min' => 0, 'step' => 0.01]) }}
{{ Form::number('heavy_rate', 'Heavy Rate', $errors, $settings, ['min' => 0, 'step' => 0.01]) }}
{{ Form::number('super_heavy_rate', 'Super Heavy Rate', $errors, $settings, ['min' => 0, 'step' => 0.01]) }}