<script>
    // Helper function to generate a Base64 image with initials
    function generateAvatarWithInitials(name) {
        const initials = getInitials(name);
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const size = 64; // Set canvas size
        canvas.width = size;
        canvas.height = size;

        // Background color
        context.fillStyle = '#0D8ABC'; 
        context.fillRect(0, 0, size, size);

        // Text settings
        context.font = 'bold 32px sans-serif';
        context.fillStyle = '#fff';
        context.textAlign = 'center';
        context.textBaseline = 'middle';
        context.fillText(initials, size / 2, size / 2);

        // Convert canvas to Base64 image
        return canvas.toDataURL('image/png');
    }

    // Helper function to extract initials from the user's name
    function getInitials(name) {
        if (!name) return '';
        const words = name.trim().split(' ');
        const initials = words.map(word => word[0]?.toUpperCase()).slice(0, 2).join('');
        return initials;
    }

    // Props passed to the component
    export let name = '';
    export let profilePhotoUrl = ''; 
    export let width = 32;
    export let height = 32;
    export let size; 

    // Reactive statement to compute avatar URL whenever inputs change
    $: avatarUrl = profilePhotoUrl || generateAvatarWithInitials(name);

    // Conditionally define width/height attributes if size is not provided
    let imgAttributes = size ? {} : { width, height };

    // Reactive style calculation
    $: imgStyle = size 
        ? `width: ${size}; height: ${size}; object-fit: cover;` 
        : 'object-fit: cover;';
</script>

<img 
    src={avatarUrl} 
    alt={name} 
    class="rounded-circle h-8 w-8 rounded-full object-cover"
    {...imgAttributes}
    style={imgStyle} />
