export default {

  methods: {
    capitalizeWords(words) {
        return words
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    },
    camelCase(className) {
        return className.toLowerCase().replace(/_([a-z])/g, function (match, letter) {
            return letter.toUpperCase();
        }) + 's';
    },
    getIcone(icone) {
      let icones = {
        'cadastrar': 'fa fa-plus-circle',
        'buscar': 'fa fa-search',
        'filtrar': 'fa fa-filter',
        'editar': 'fa fa-pencil-square',
        'deletar': 'fa fa-trash-o',
        'link': 'fa fa-external-link-square',
        'salvar': 'fa fa-floppy-o',
        'fechar': 'fa fa-times',
        'info': 'fa fa-info-circle',
        'pdf': 'fa fa-file-pdf-o',
        'texto': 'fa fa-file-text-o',
        'empresa': 'fa fa-building',
        'empresa': 'fa fa-building',
        'usuarios': 'fa fa fa-users',
        'usuario': 'fa fa-user',
        'medico': 'fa fa-user-md',
        'download': 'fa fa-cloud-download',
        'upload': 'fa-cloud-upload',
        'email': 'fa fa-envelope',
        'historico': 'fa fa-history'
      }

      if (icones[icone]) {
        return icones[icone]
      }

      return null;
    },

    randomMumber(min, max) {
      min = (!min ? 1 : min)
      max = (!max ? 10 : max)
      return Math.random() * (max - min) + min;
    },

    randomString() {
      let math = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
      return this.toMd5(math + (new Date()).getTime())
    },

    arrFindByField(arr, field, value, index) {
      if (!field || !arr || !arr.length > 0) return null;

      var arr = arr.filter(x => x[field] == value);
      var arrIndex = arr.findIndex(x => x[field] == value);

      if (index) return (arrIndex);
      if (arr.length == 1) return arr[0];

      return null;
    },

    onlyNumber(value) {
      if (!value) return "";

      value = value.toString();

      return value.replace(/[\. ,:-]+/g, "-").replace(/[^0-9\.]+/g, "");
    },

    onlyText(value) {
      if (!value) return "";

      value = this.toString(value);
      return value.replace(/(<([^>]+)>)/gi, "");
    },

    isEmail(value) {
      const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

      return value && pattern.test(value)
    },

    isDate(value) {
      if (!value) return false;

      let arrayValue = value.toString().split('/');

      if (arrayValue.length != 3) return false;

      let dia = arrayValue[0];
      let mes = arrayValue[1];
      let ano = arrayValue[2];
      let data = ano + '-' + mes + '-' + dia;

      if (parseInt(mes) > 12 || parseInt(mes) < 1 || parseInt(dia) < 1 || parseInt(dia) > 31 || parseInt(ano) < 1900) return false;

      return moment(data).isValid();
    },

    isCpf(cpf) {
      cpf = cpf.replace(/[^\d]+/g, '');
      if (cpf == '') return false;
      // Elimina CPFs invalidos conhecidos	
      if (cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999")
        return false;
      // Valida 1o digito	
      let add = 0;
      for (var i = 0; i < 9; i++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
      let rev = 11 - (add % 11);
      if (rev == 10 || rev == 11)
        rev = 0;
      if (rev != parseInt(cpf.charAt(9)))
        return false;
      // Valida 2o digito	
      add = 0;
      for (i = 0; i < 10; i++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
      rev = 11 - (add % 11);
      if (rev == 10 || rev == 11)
        rev = 0;
      if (rev != parseInt(cpf.charAt(10)))
        return false;
      return true;
    },

    mascara(val, mask) {

      if (!val) return val;

      val = this.onlyNumber(val);

      let num = this.onlyNumber(val);

      if (this.isCnpj(num)) {
        mask = 'cnpj';
      }
      else if (this.isCpf(num)) {
        mask = 'cpf';
      }
      else if (num.toString().length == 10) {
        mask = 'telefone';
      }
      else if (num.toString().length == 1) {
        mask = 'celular';
      }

      switch (mask) {

        case 'cnpj':
          mask = '##.###.###/####-##'
          break;

        case 'cpf':
          mask = '###.###.###-##'
          break;

        case 'telefone':
          mask = '(##) ####-####'
          break;

        case 'celular':
          mask = '(##) #####-#####'
          break;

        default:
          mask = ''
          break;
      }

      let maskared = '';
      let k = 0;

      for (let i = 0; i <= mask.length - 1; i++) {

        if (mask[i] == '#') {
          if ((val[k]))
            maskared += val[k++];
        } else {
          if ((mask[i]))
            maskared += mask[i];
        }
      }

      return maskared;

    },

    isCnpj(value) {

      if (!value) return false;

      let cnpj = this.onlyNumber(value);

      // Valida a quantidade de caracteres
      if (cnpj.length !== 14)
        return false

      // Elimina inválidos com todos os caracteres iguais
      if (/^(\d)\1+$/.test(cnpj))
        return false

      // Cáculo de validação
      let t = cnpj.length - 2,
        d = cnpj.substring(t),
        d1 = parseInt(d.charAt(0)),
        d2 = parseInt(d.charAt(1)),
        calc = x => {
          let n = cnpj.substring(0, x),
            y = x - 7,
            s = 0,
            r = 0

          for (let i = x; i >= 1; i--) {
            s += n.charAt(x - i) * y--;
            if (y < 2)
              y = 9
          }

          r = 11 - s % 11
          return r > 9 ? 0 : r
        }

      return calc(t) === d1 && calc(t + 1) === d2
    },

    isNumeric(value) {
      const pattern = /[0-9]/

      return value && pattern.test(value)
    },

    buildFormData(formData, data, parentKey) {

      if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
        Object.keys(data).forEach(key => {
          this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
        });
      } else {
        const value = data == null ? '' : data;

        formData.append(parentKey, value);
      }
    },

    toFormUpload(data) {
      const formData = new FormData();

      this.buildFormData(formData, data);

      return formData;
    },

    booleanToInteger(form) {
      for (var key in form) {
        if (form[key] === true) form[key] = 1;

        if (form[key] === false) form[key] = 0;
      }

      return form;
    },

    base64Decode(value) {
      try {
        return Base64.decode(value);
      } catch (err) {
        return null;
      }
    },

    base64Encode(value) {
      try {
        return Base64.encode(value);
      } catch (err) {
        return null;
      }
    },

    fileToBase64(file) {

      var reader = new FileReader();
      return new Promise((resolve, reject) => {
        reader.onerror = () => { reader.abort(); reject(new Error("Error parsing file")); }
        reader.onload = function () {

          //This will result in an array that will be recognized by C#.NET WebApi as a byte[]
          let bytes = Array.from(new Uint8Array(this.result));

          //if you want the base64encoded file you would use the below line:
          let base64StringFile = btoa(bytes.map((item) => String.fromCharCode(item)).join(""));

          //Resolve the promise with your custom file structure
          resolve({
            size: file.size,
            content: base64StringFile,
            name: file.name,
            type: file.type
          });
        }
        reader.readAsArrayBuffer(file);
      });

    },

    utf8Decode(t) {
      if (!t) return t;

      try {
        return decodeURIComponent(escape(t));
      } catch (err) {
        return t;
      }
    },

    toDate(date, disableHours, format) {
      if (!date) {
        date = new Date();
      }

      if (!format) {
        format = "DD/MM/YYYY HH:mm:ss";

        if (disableHours) {
          format = "DD/MM/YYYY";
        }
      }

      return moment(date).format(format);
    },

    convertDate(date, format) {
      if (!date) {
        return new Date();
      }

      format = (format ? format : 'DD/MM/YYYY');

      return moment(date).format(format);
    },

    addDate(value, date, type = 'D', format) {

      if (!date) {
        date = new Date();
      } else {
        date = new Date(date);
      }

      switch (type) {
        case "y":
          date = moment(date).add(value, 'years').toDate();
          break;
        case "M":
          date = moment(date).add(value, 'months').toDate();
          break;
        case "D":
          date = moment(date).add(value, 'days').toDate();
          break;
        case "h":
          date = moment(date).add(value, 'hours').toDate();
          break;
        case "m":
          date = moment(date).add(value, 'minutes').toDate();
          break;
        case "s":
          date = moment(date).add(value, 'secounds').toDate();
          break;
        default:
          date = moment(date).add(value, 'days').toDate();
      }

      if (format) {
        return moment(date).format(format);
      }
      return date;
    },

    diffDays(date1, date2, type) {
      type = !type ? "d" : type;
      date1 = !date1 ? moment() : moment(date1);
      date2 = !date2 ? moment() : moment(date2);

      switch (type) {
        case "y":
          return date1.diff(date2, "years", true);
        case "d":
          return date1.diff(date2, "days", true);
        case "h":
          return date1.diff(date2, "hours", true);
        case "m":
          return date1.diff(date2, "minutes", true);
        case "s":
          return date1.diff(date2, "seconds", true);
        default:
          return date1.diff(date2, "days", true);
      }

    },

    moneyToFloat(value) {
      if (!value || value == "") {
        return 0;
      }

      value = this.toString(value);

      return parseFloat(value.replace(".", "").replace(",", "."));
    },

    floatToMoney(value) {
      if (!value) {
        return "0,00";
      }

      var formatter = new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL"
      });

      value = formatter
        .format(value)
        .replace("R$", "")
        .trim();

      return value;
    },

    formataCampo(text, left, format) {

      format = (!format ? '0' : format);
      left = (!left ? 0 : left);

      if (!text) return text;

      return text.toString().padStart(left, format);
    },

    toMb: function (bytes) {

      if (!bytes) return '0 MB';

      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
      if (bytes === 0) return 'n/a'
      const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10)
      if (i === 0) return `${bytes} ${sizes[i]}`
      return `${(bytes / (1024 ** i)).toFixed(1)} ${sizes[i]}`

    },

    toString(value) {
      if (value == null || value == undefined) return '';

      return value.toString();
    },

    toMd5(text) {

      try {
        text = this.toString(text);
        text = md5(text);
        return text;
      } catch (e) {
        return text;
      }
    },

    validateEmail(email) {

      email = (email ? email.trim() : null);

      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        return true
      }
      return false
    },

    textLimit(value, limit) {

      limit = (limit ? limit : 100);

      if (value && value.length > limit) {
        return value.substring(0, limit) + "...";
      }

      return value;
    },

    removerAcentos(source = '') {
      if (!source) { return '' }

      var accent = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g, // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g, // C, c
      ],
        noaccent = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c']

      for (var i = 0; i < accent.length; i++) {
        source = source.replace(accent[i], noaccent[i])
      }

      return source
    },


    getEstado(search = null) {
      let arrayEstados = [
        {
          "uf": "AC",
          "estado": "Acre"
        },
        {
          "uf": "AL",
          "estado": "Alagoas"
        },
        {
          "uf": "AP",
          "estado": "Amapá"
        },
        {
          "uf": "AM",
          "estado": "Amazonas"
        },
        {
          "uf": "BA",
          "estado": "Bahia"
        },
        {
          "uf": "CE",
          "estado": "Ceará"
        },
        {
          "uf": "DF",
          "estado": "Distrito Federal"
        },
        {
          "uf": "ES",
          "estado": "Espírito Santo"
        },
        {
          "uf": "GO",
          "estado": "Goiás"
        },
        {
          "uf": "MA",
          "estado": "Maranhão"
        },
        {
          "uf": "MT",
          "estado": "Mato Grosso"
        },
        {
          "uf": "MS",
          "estado": "Mato Grosso do Sul"
        },
        {
          "uf": "MG",
          "estado": "Minas Gerais"
        },
        {
          "uf": "PA",
          "estado": "Pará"
        },
        {
          "uf": "PB",
          "estado": "Paraíba"
        },
        {
          "uf": "PR",
          "estado": "Paraná"
        },
        {
          "uf": "PE",
          "estado": "Pernambuco"
        },
        {
          "uf": "PI",
          "estado": "Piauí"
        },
        {
          "uf": "RJ",
          "estado": "Rio de Janeiro"
        },
        {
          "uf": "RN",
          "estado": "Rio Grande do Norte"
        },
        {
          "uf": "RS",
          "estado": "Rio Grande do Sul"
        },
        {
          "uf": "RO",
          "estado": "Rondônia"
        },
        {
          "uf": "RR",
          "estado": "Roraima"
        },
        {
          "uf": "SP",
          "estado": "São Paulo"
        },
        {
          "uf": "SC",
          "estado": "Santa Catarina"
        },
        {
          "uf": "SE",
          "estado": "Sergipe"
        },
        {
          "uf": "TO",
          "estado": "Tocantins"
        }
      ];


      return arrayEstados.filter(function (objEstado) {
        objEstado.estado = objEstado.estado.toUpperCase();

        return (
          !search || objEstado.uf.indexOf(search) !== -1
        )
      })

    },

    pluck(array, key) {
      return array.map(o => o[key]);
    }
  },


};