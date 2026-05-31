function handleWhatsAppClick(phone, productName, price) {
  if (!phone || phone.trim() === '' || phone === '-' || phone.length < 9) {
    if (typeof window.triggerToast === 'function') {
      window.triggerToast('toast_error', 'Seller WhatsApp contact is invalid or unavailable.');
    }
    return;
  }

  // Format if starts with 0 changed to 62
  let formattedPhone = phone;
  if (formattedPhone.startsWith('0')) {
    formattedPhone = '62' + formattedPhone.substring(1);
  }

  const message = `Hello, I am interested in the product on SellOn:\n\n📦 Product: ${productName}\n💰 Price: Rp ${price}\n\nIs it still available?`;
  window.open(`https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`, '_blank');
}