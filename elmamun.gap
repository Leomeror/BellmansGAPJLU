type Rope = extern

signature sig_elmamun(alphabet, answer) {
  answer number(int);
  answer add(answer, alphabet, answer);
  answer mult(answer, alphabet, answer);
  answer minus(answer, alphabet, answer);
  choice [answer] h([answer]);
}

algebra alg_enum auto enum;
algebra alg_count auto count;

algebra alg_pretty implements sig_elmamun(alphabet=char, answer=Rope) {
  Rope number(int value) {
    Rope res;
    append(res, value);
    return res;
  }
  Rope add(Rope left, char opSymbol, Rope right) {
    Rope res;
    append(res, '(');
    append(res, left);
    append(res, opSymbol);
    append(res, right);
    append(res, ')');
    return res;
  }
  Rope mult(Rope left, char opSymbol, Rope right) {
    Rope res;
    append(res, '(');
    append(res, left);
    append(res, opSymbol);
    append(res, right);
    append(res, ')');
    return res;
  }
  Rope minus(Rope left, char opSymbol, Rope right) {
    Rope res;
    append(res, '(');
    append(res, left);
    append(res, opSymbol);
    append(res, right);
    append(res, ')');
    return res;
  }
  choice [Rope] h([Rope] candidates) {
    return candidates;
  }
}

algebra alg_buyer implements sig_elmamun(alphabet=char, answer=int) {
  int number(int value) {
    return value;
  }
  int add(int left, char opSymbol, int right) {
    return left + right;
  }
  int mult(int left, char opSymbol, int right) {
    return left * right;
  }
  int minus(int left, char opSymbol, int right) {
    return left - right;
  }
  choice [int] h([int] candidates) {
    return list(minimum(candidates));
  }
}
algebra alg_seller extends alg_buyer {
  choice [int] h([int] candidates) {
    return list(maximum(candidates));
  }
}

algebra alg_time implements sig_elmamun(alphabet=char, answer=int) {
  int number(int value) {
    return 0;
  }
  int add(int left, char opSymbol, int right) {
    if (left > right) {
      return left + 2;
    } else {
      return right + 2;
    }
  }
  int mult(int left, char opSymbol, int right) {
    if (left > right) {
      return left + 5;
    } else {
      return right + 5;
    }
  }
  int minus(int left, char opSymbol, int right) {
    if (left > right) {
      return left + 3;
    } else {
      return right + 3;
    }
  }
  choice [int] h([int] candidates) {
    return list(minimum(candidates));
  }
}

grammar gra_elmamun uses sig_elmamun(axiom = formula) {
  formula = number(INT)
	  | add(formula, CHAR('+'), formula)
	  | mult(formula, CHAR('*'), formula)
	  | minus(formula, CHAR('-'), formula)
	  # h;
}

instance pp = gra_elmamun(alg_pretty);
instance enum = gra_elmamun(alg_enum);
instance sellerpp = gra_elmamun(alg_seller * alg_pretty);
instance buyerpp = gra_elmamun(alg_buyer * alg_pretty);
instance ppbuyer = gra_elmamun(alg_pretty * alg_buyer);
instance timepp = gra_elmamun(alg_time * alg_pretty);

// example input:
// "1+2*3*4+5"
