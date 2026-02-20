<h2>New Quote Request</h2>

<p><strong>Product:</strong> {{ $data['product_name'] }}</p>

<p><strong>Name:</strong> {{ $data['name'] }}</p>
<p><strong>Phone:</strong> {{ $data['phone'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>

<p>
<strong>Product Page:</strong> 
<a href="{{ $data['product_url'] }}">View Product</a>
</p>

@if(!empty($data['message']))
<p><strong>Message:</strong><br>{{ $data['message'] }}</p>
@endif