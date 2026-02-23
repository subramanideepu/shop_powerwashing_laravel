<div class="row">
    <div class="col-md-8">

        {{ Form::number(
            'super_light_cost',
            'Super Light Cost',
            $errors,
            $settings
        ) }}

        {{ Form::number(
            'light_cost',
            'Light Cost',
            $errors,
            $settings
        ) }}

        {{ Form::number(
            'medium_cost',
            'Medium Cost',
            $errors,
            $settings
        ) }}

        {{ Form::number(
            'heavy_cost',
            'Heavy Cost',
            $errors,
            $settings
        ) }}

        {{ Form::number(
            'super_heavy_cost',
            'Super Heavy Cost',
            $errors,
            $settings
        ) }}

    </div>
</div>