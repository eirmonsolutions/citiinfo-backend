<h2>New Business Enquiry</h2>

<p><strong>Business:</strong> {{ $listing->business_name }}</p>
<p><strong>Name:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Phone:</strong> {{ $data['phone'] }}</p>

<p><strong>Message:</strong></p>
<p>{{ $data['message'] ?? 'N/A' }}</p>