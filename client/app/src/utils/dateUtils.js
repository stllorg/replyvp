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

export const formatFullDateTime = (timestamp, shortMonthAndDay = false) => {
  let date;

  if (timestamp instanceof Date) {
    date = timestamp;
  } else if (typeof timestamp === 'string') {
    date = new Date(timestamp);
  } else {
    console.warn("Invalid timestamp provided to formatFullDateTime:", timestamp);
    return "N/A";
  }

  if (isNaN(date.getTime())){
    console.error("Failed to parse date for formatFullDateTime:", timestamp);
    return "Data Inválida";
  }

  const options = {
    hour: "2-digit",
    minute: "2-digit",
  };

  if (shortMonthAndDay) {
    options.day = "2-digit";
    options.month = "2-digit";
    options.year = "numeric";
  } else {
    options.day = "numeric";
    options.month = "long";
    options.year = "numeric";
  }

  return new Intl.DateTimeFormat("pt-BR", options).format(date);
};