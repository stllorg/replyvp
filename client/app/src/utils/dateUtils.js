export const formatMessageTime = (timestamp) => {
  let date;

  if (timestamp instanceof Date) {
    date = timestamp;
  } else if (typeof timestamp === 'string') {
    date = new Date(timestamp);
  } else {
    console.warn("Invalid timestamp provided to formatMessageTime:", timestamp);
    return "N/A";
  }

  if (isNaN(date.getTime())){
    console.error("Failed to parse date for formatMessageTime:", timestamp);
    return "Data Inválida";
  }


  return new Intl.DateTimeFormat("pt-BR", {
    hour: "2-digit",
    minute: "2-digit",
  }).format(date);
};