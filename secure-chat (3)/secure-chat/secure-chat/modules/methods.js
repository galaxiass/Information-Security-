class RSA{
  constructor() {}

  findRandomPrime(p){
      const min = 300;
      const max = 999;
      for (;;) {
        const i = Math.floor(Math.random() * max) + min;
        if (this.isPrime(i) && i != p) {
            return i;
        }
      }
  }

  isPrime(num){
    if(num % 2 == 0) {
        return false;
    }
    
    for(let i = 3; i <= Math.ceil(Math.sqrt(num)); i = i + 2) {
        if(num % i == 0)
            return false;
    }
    return true;
  }

  compute_n(p, q){
    return p * q;
  }

  eular_z(p, q){
    return (p - 1) * (q - 1);
  }

  find_e(z){
    for(let i = 2; i < z; i++){
      if(this.coprime(i, z)){
        return i;
      }
    }
  }

  gcd(e, z){
      if (e == 0 || z == 0){
          return 0;
      }

      if (e == z){
          return e;
      }

      if (e > z){
          return this.gcd(e - z, z);
      }

      return this.gcd(e, z - e);
  }

  coprime(e, z){
      if (this.gcd(e, z) == 1){
        return true;
      }
      return false;
  }

  find_d(e, z) {
    for(let d=1;;d++){
      if(((d * e) % z) == 1){
        return d;
      }
    }
  }

  encrypt(m, e, n){
    let c = "";
    let newChar = "";
    let everySeparate = "";
    for(let i = 0; i < m.length; i++){
      newChar = bcpowmod(m.charCodeAt(i), e, n);
      everySeparate+=newChar.length;
      c+=newChar;
    }
    return array(c, everySeparate);
  }
}
